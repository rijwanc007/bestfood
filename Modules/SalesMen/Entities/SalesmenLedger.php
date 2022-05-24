<?php

namespace Modules\SalesMen\Entities;

use App\Models\BaseModel;
use Modules\SalesMen\Entities\Salesmen;
use Modules\Account\Entities\ChartOfAccount;

class SalesmenLedger extends BaseModel
{
    protected $table = 'transactions';
    protected $order = ['transactions.voucher_date' => 'asc'];
    protected $fillable = ['chart_of_account_id', 'voucher_no', 'voucher_type', 'voucher_date', 'description', 'debit', 
    'credit', 'posted', 'approve', 'created_by', 'modified_by'];
    private const TYPE = 'Account Payable'; 

    public function coa()
    {
        return $this->belongsTo(ChartOfAccount::class,'chart_of_account_id','id');
    }

    public function salesmen()
    {
        return $this->hasOneThrough(Salesmen::class,ChartOfAccount::class,'salesmen_id','chart_of_account_id','id','id');
    }

    /******************************************
     * * * Begin :: Custom Datatable Code * * *
    *******************************************/
    //custom search column property
    protected $salesmen_id; 
    protected $from_date; 
    protected $to_date; 

    //methods to set custom search property value
    public function setSalesmenID($salesmen_id)
    {
        $this->salesmen_id = $salesmen_id;
    }
    public function setFromDate($from_date)
    {
        $this->from_date = $from_date;
    }
    public function setToDate($to_date)
    {
        $this->to_date = $to_date;
    }

    private function get_datatable_query()
    {
        //set column sorting index table column name wise (should match with frontend table header)

        $this->column_order = ['transactions.voucher_date','transactions.description', 'transactions.voucher_no','transactions.debit','transactions.credit',null];
        
        
        $query = self::select('transactions.*','coa.id as coa_id','coa.code','coa.name','coa.parent_name','s.id as salesmen_id','s.name','s.phone')
        ->join('chart_of_accounts as coa','transactions.chart_of_account_id','=','coa.id')
        ->join('salesmen as s','coa.salesmen_id','s.id')
        ->where(['coa.parent_name'=>self::TYPE,'transactions.approve'=>1]);

        //search query
        if (!empty($this->salesmen_id)) {
            $query->where('s.id', $this->salesmen_id);
        }
        if (!empty($this->from_date)) {
            $query->where('transactions.voucher_date', '>=',$this->from_date);
        }
        if (!empty($this->to_date)) {
            $query->where('transactions.voucher_date', '<=',$this->to_date);
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
        return self::select('transactions.*','coa.id as coa_id','coa.code','coa.name','coa.parent_name','s.id as salesmen_id','s.name','s.phone')
        ->join('chart_of_accounts as coa','transactions.chart_of_account_id','=','coa.id')
        ->join('salesmen as s','coa.salesmen_id','s.id')
        ->where(['coa.parent_name'=>self::TYPE,'transactions.approve'=>1])->get()->count();
    }
    /******************************************
     * * * End :: Custom Datatable Code * * *
    *******************************************/
}
