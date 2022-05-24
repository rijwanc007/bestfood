<?php

namespace Modules\Supplier\Entities;

use App\Models\BaseModel;
use Modules\Supplier\Entities\Supplier;
use Modules\Account\Entities\ChartOfAccount;

class SupplierAdvance extends BaseModel
{
    protected $table = 'transactions';
    protected $order = ['transactions.id' => 'desc'];
    protected $fillable = ['chart_of_account_id', 'voucher_no', 'voucher_type', 'voucher_date', 'description', 'debit', 
    'credit', 'posted', 'approve', 'created_by', 'modified_by'];
    private const TYPE = 'Advance'; 

    public function coa()
    {
        return $this->belongsTo(ChartOfAccount::class,'chart_of_account_id','id');
    }

    public function supplier()
    {
        return $this->hasOneThrough(Supplier::class,ChartOfAccount::class,'supplier_id','chart_of_account_id','id','id');
    }

    /******************************************
     * * * Begin :: Custom Datatable Code * * *
    *******************************************/
    //custom search column property
    protected $supplier_id; 
    protected $type; 

    //methods to set custom search property value
    public function setSupplierID($supplier_id)
    {
        $this->supplier_id = $supplier_id;
    }
    public function setType($type)
    {
        $this->type = $type;
    }

    private function get_datatable_query()
    {
        //set column sorting index table column name wise (should match with frontend table header)
        if (permission('supplier-advance-bulk-delete')){
            $this->column_order = [null,'transactions.id','s.name', null,null,'transactions.approve','transactions.created_at',null];
        }else{
            $this->column_order = ['transactions.id','s.name', null,null,'transactions.created_at',null];
        }
        
        $query = self::select('transactions.*','coa.id as coa_id','coa.code','coa.name as account_name','coa.parent_name','s.id as supplier_id','s.name','s.mobile')
        ->join('chart_of_accounts as coa','transactions.chart_of_account_id','=','coa.id')
        ->join('suppliers as s','coa.supplier_id','s.id')
        ->where('transactions.voucher_type',self::TYPE);

        //search query
        if (!empty($this->supplier_id)) {
            $query->where('s.id', $this->supplier_id);
        }
        if (!empty($this->type) && $this->type == 'debit') {
            $query->where('transactions.debit', '!=',0);
        }
        if (!empty($this->type) && $this->type == 'credit') {
            $query->where('transactions.credit', '!=',0);
        }

        //order by data fetching code
        if (isset($this->orderValue) && isset($this->dirValue)) { //orderValue is the index number of table header and dirValue is asc or desc
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue); //fetch data order by matching column
        } else if (isset($this->order)) {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }
        return $query;
    }

    public function getDatatableList()
    {
        $query = $this->get_datatable_query();
        if ($this->lengthVlaue != -1) {
            $query->offset($this->startVlaue)->limit($this->lengthVlaue);
        }
        return $query->get();
    }

    public function count_filtered()
    {
        $query = $this->get_datatable_query();
        return $query->get()->count();
    }

    public function count_all()
    {
        return self::select('transactions.*','coa.id as coa_id','coa.code','s.id as supplier_id','s.name','s.mobile')->join('chart_of_accounts as coa','transactions.chart_of_account_id','=','coa.id')
        ->join('suppliers as s','coa.supplier_id','s.id')->where('transactions.voucher_type',self::TYPE)->get()->count();
    }
    /******************************************
     * * * End :: Custom Datatable Code * * *
    *******************************************/


}
