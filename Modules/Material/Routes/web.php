<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth']], function () {
    //Material Routes
    Route::get('material', 'MaterialController@index')->name('material');
    Route::group(['prefix' => 'material', 'as'=>'material.'], function () {
        Route::post('datatable-data', 'MaterialController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'MaterialController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'MaterialController@edit')->name('edit');
        Route::post('view', 'MaterialController@show')->name('view');
        Route::post('delete', 'MaterialController@delete')->name('delete');
        Route::post('bulk-delete', 'MaterialController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'MaterialController@change_status')->name('change.status');
        Route::get('generate-code', 'MaterialController@generateMaterialCode')->name('generate.code');
    });
    Route::post('material-autocomplete-search', 'MaterialController@autocomplete_search_material');
    Route::post('material-search', 'MaterialController@search_material')->name('material.search');
    Route::post('warehouse-wise-materials', 'MaterialController@warehouse_wise_materials')->name('warehouse.wise.materials');
    Route::post('material-search-by-id', 'MaterialController@search_material_by_id')->name('material.search.by.id');
});
