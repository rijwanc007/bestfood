<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/expense', function (Request $request) {
    return $request->user();
});
Route::get('test-migration',function(){
   \DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
$tables = \DB::select('SHOW TABLES');
foreach($tables as $table){
    $table = implode(json_decode(json_encode($table), true));
    \Schema::drop($table);
    echo 'Dropped `'.$table . '`. ';
}
\DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
   if(\Artisan::call('migrate:reset', ['--force' => true]))
   {
       return true;
   }
   return false;
});