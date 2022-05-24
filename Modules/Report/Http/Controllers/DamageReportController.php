<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController;
use Modules\Report\Entities\DamageReport;

class DamageReportController extends BaseController
{
    public function __construct(DamageReport $model)
    {
        $this->model = $model;
    }
    
    public function index()
    {
        if(permission('damage-report-access')){
            $this->setPageData('Damage Report','Damage Report','fas fa-file',[['name' => 'Damage Report']]);
            $data = [
                'warehouses'  => DB::table('warehouses')->where('status',1)->pluck('name','id')
            ];
            return view('report::damage-report',$data);
        }else{
            return $this->access_blocked();
        }

    }

    public function get_datatable_data(Request $request)
    {
        if($request->ajax()){
            if(permission('damage-report-access')){

                if (!empty($request->start_date)) {
                    $this->model->setStartDate($request->start_date);
                }
                if (!empty($request->end_date)) {
                    $this->model->setEndDate($request->end_date);
                }
                if (!empty($request->warehouse_id)) {
                    $this->model->setWarehouseID($request->warehouse_id);
                }

                $this->set_datatable_default_properties($request);//set datatable default properties
                $list = $this->model->getDatatableList();//get table data
                $data = [];
                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;

                    $product = '';
                    if($value->id)
                    {
                       $product = $this->products($value->id);
                    }
                    
                    $row = [];
                    $row[] = $no;
                    $row[] = $value->return_no;
                    $row[] = $value->memo_no;
                    $row[] = $value->shop_name.' - '.$value->customer_name;
                    $row[] = $value->salesman_name;
                    $row[] = $value->district_name;
                    $row[] = $value->upazila_name;
                    $row[] = $value->route_name;
                    $row[] = $value->area_name;
                    $row[] = $value->total_return_items.'('.$value->total_return_qty.')';
                    $row[] = $product['product'];
                    $row[] = $product['code'];
                    $row[] = $product['batch_no'];
                    $row[] = $product['unit'];
                    $row[] = $product['return_qty'];
                    $row[] = $product['return_rate'];
                    $row[] = $product['deduction_rate'];
                    $row[] = $product['deduction_amount'];
                    $row[] = $product['subtotal'];
                    $row[] = number_format($value->total_deduction,2);
                    $row[] = number_format($value->grand_total,2);
                    $row[] = date('d-M-Y',strtotime($value->return_date));
                    $data[] = $row;
                }
                return $this->datatable_draw($request->input('draw'),$this->model->count_all(),
                $this->model->count_filtered(), $data);
            }   
        }else{
            return response()->json($this->unauthorized());
        }
    }

    protected function products($sale_return_id)
    {
        $data = [];
        $return_products = DB::table('sale_return_products as srp')
        ->leftjoin('products as p','srp.product_id','=','p.id')
        ->leftjoin('units as u','srp.unit_id','=','u.id')
        ->select('srp.*','p.name','p.code','u.unit_name','u.unit_code')
        ->where('srp.sale_return_id',$sale_return_id)
        ->get();
        $product = $code = $batch_no = $unit = $return_qty = $return_rate = $deduction_rate = $deduction_amount = $subtotal = '';         
        if($return_products)
        {
            foreach ($return_products as $item) {
                $product          .= "<li class='pl-3'>".$item->name."</li>";
                $code             .= "<li class='pl-3'>".$item->code."</li>";
                $batch_no         .= "<li class='pl-3'>".$item->batch_no."</li>";
                $unit             .= "<li>".$item->unit_name."</li>";
                $return_qty       .= "<li>".number_format($item->return_qty,2,'.','')."</li>";
                $return_rate      .= "<li class='pr-3'>".number_format($item->product_rate,2,'.','')."</li>";
                $deduction_rate   .= "<li class='pr-3'>".number_format($item->deduction_rate,2,'.','')."</li>";
                $deduction_amount .= "<li class='pr-3'>".number_format($item->deduction_amount,2,'.','')."</li>";
                $subtotal         .= "<li class='pr-3'>".number_format($item->total,2,'.','')."</li>";
            }
        }
        return $data = [ 
            'product'          => '<ul style="list-style:none;margin:0;padding:0;">'.$product.'</ul>',
            'code'             => '<ul style="list-style:none;margin:0;padding:0;">'.$code.'</ul>',
            'batch_no'         => '<ul style="list-style:none;margin:0;padding:0;">'.$batch_no.'</ul>',
            'unit'             => '<ul style="list-style:none;margin:0;padding:0;">'.$unit.'</ul>',
            'return_qty'       => '<ul style="list-style:none;margin:0;padding:0;">'.$return_qty.'</ul>',
            'return_rate'      => '<ul style="list-style:none;margin:0;padding:0;">'.$return_rate.'</ul>',
            'deduction_rate'   => '<ul style="list-style:none;margin:0;padding:0;">'.$deduction_rate.'</ul>',
            'deduction_amount' => '<ul style="list-style:none;margin:0;padding:0;">'.$deduction_amount.'</ul>',
            'subtotal'         => '<ul style="list-style:none;margin:0;padding:0;">'.$subtotal.'</ul>',
        ];   
    }
}
