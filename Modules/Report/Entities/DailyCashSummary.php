<?php

namespace Modules\Report\Entities;

use App\Models\BaseModel;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\Entities\Transaction;

class DailyCashSummary extends BaseModel
{
  public function chartOfAccount(){
      return $this->hasOne(ChartOfAccount::class,'chart_of_account_id','id');
  }
  public function collectTransactionID(){
      $bank = ChartOfAccount::where('parent_name','Cash At Bank')->get()->toArray();
      $mobileBank = ChartOfAccount::where('parent_name','Cash At Mobile Bank')->get()->toArray();
      $cash = ChartOfAccount::where('name','Cash In Hand')->where('parent_name','Cash & Cash Equivalent')->get()->toArray();
      $collection = [];
      foreach (array_merge($bank,$mobileBank,$cash) as $value){
          $collection[] = $value['id'];
      }
      return $collection;
  }
  public function sale($date){
      $sales      = Transaction::where('voucher_type','INVOICE')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_account_id',$this->collectTransactionID())->get();
      $totalSales = Transaction::where('voucher_type','INVOICE')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_account_id',$this->collectTransactionID())->sum('debit');
      return [$sales,$totalSales];
  }
//   public function tenant($date){
//       $tenant     = Transaction::where('voucher_type','Tenant Receivable')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_account_id',$this->collectTransactionID())->get();
//       $totalTenant= Transaction::where('voucher_type','Tenant Receivable')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_account_id',$this->collectTransactionID())->sum('debit');
//       return [$tenant,$totalTenant];
//   }
  public function purchase($date){
      $purchase        = Transaction::where('voucher_type','Purchase')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_account_id',$this->collectTransactionID())->get();
      $totalPurchase   = Transaction::where('voucher_type','Purchase')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_account_id',$this->collectTransactionID())->sum('credit');
      return [$purchase,$totalPurchase];
  }
  public function personalLoan($date){
      $incomePersonalLoan       = Transaction::where('voucher_type','PL')->where('created_at','LIKE','%'.$date.'%')->where('credit',0)->whereIn('chart_of_account_id',$this->collectTransactionID())->get();
      $totalIncomePersonalLoan  = Transaction::where('voucher_type','PL')->where('created_at','LIKE','%'.$date.'%')->where('credit',0)->whereIn('chart_of_account_id',$this->collectTransactionID())->sum('debit');
      $expensePersonalLoan      = Transaction::where('voucher_type','PL')->where('created_at','LIKE','%'.$date.'%')->where('debit',0)->whereIn('chart_of_account_id',$this->collectTransactionID())->get();
      $totalExpensePersonalLoan = Transaction::where('voucher_type','PL')->where('created_at','LIKE','%'.$date.'%')->where('debit',0)->whereIn('chart_of_account_id',$this->collectTransactionID())->sum('credit');
      return [$incomePersonalLoan,$totalIncomePersonalLoan,$expensePersonalLoan,$totalExpensePersonalLoan];
  }
  public function officialLoan($date){
      $incomeOfficialLoan       = Transaction::where('voucher_type','EMPSALOL')->where('created_at','LIKE','%'.$date.'%')->where('credit',0)->whereIn('chart_of_account_id',$this->collectTransactionID())->get();
      $totalIncomeOfficialLoan  = Transaction::where('voucher_type','EMPSALOL')->where('created_at','LIKE','%'.$date.'%')->where('credit',0)->whereIn('chart_of_account_id',$this->collectTransactionID())->sum('debit');
      $expenseOfficialLoan      = Transaction::where('voucher_type','EMPSALOL')->where('created_at','LIKE','%'.$date.'%')->where('debit',0)->whereIn('chart_of_account_id',$this->collectTransactionID())->get();
      $totalExpenseOfficialLoan = Transaction::where('voucher_type','EMPSALOL')->where('created_at','LIKE','%'.$date.'%')->where('debit',0)->whereIn('chart_of_account_id',$this->collectTransactionID())->sum('credit');
      return [$incomeOfficialLoan,$totalIncomeOfficialLoan,$expenseOfficialLoan,$totalExpenseOfficialLoan];
  }
//   public function machinePurchase($date){
//       $machinePurchase          = Transaction::where('voucher_type','Machine Purchase')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_account_id',$this->collectTransactionID())->get();
//       $totalMachinePurchase     = Transaction::where('voucher_type','Machine Purchase')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_account_id',$this->collectTransactionID())->sum('credit');
//       return [$machinePurchase,$totalMachinePurchase];
//   }
//   public function machineService($date){
//       $machineService           = Transaction::where('voucher_type','Maintenance Service')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_account_id',$this->collectTransactionID())->get();
//       $totalMachineService      = Transaction::where('voucher_type','Maintenance Service')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_account_id',$this->collectTransactionID())->sum('debit');
//       return [$machineService,$totalMachineService];
//   }
//   public function transportService($date){
//       $transportService         = Transaction::where('voucher_type','Transport Service')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_account_id',$this->collectTransactionID())->get();
//       $totalTransportService    = Transaction::where('voucher_type','Transport Service')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_account_id',$this->collectTransactionID())->sum('debit');
//       return [$transportService,$totalTransportService];
//   }
//   public function laborBill($date){
//       $laborBill       = Transaction::where('voucher_type','Labor Bill')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_account_id',$this->collectTransactionID())->get();
//       $totalLaborBill  = Transaction::where('voucher_type','Labor Bill')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_account_id',$this->collectTransactionID())->sum('credit');
//       return [$laborBill,$totalLaborBill];
//   }
  public function expense($date){
      $expense         = Transaction::where('voucher_type','Expense')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_account_id',$this->collectTransactionID())->get();
      $totalExpense    = Transaction::where('voucher_type','Expense')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_account_id',$this->collectTransactionID())->sum('credit');
      return [$expense,$totalExpense];
  }
  public function cash($date){
      $cash = ChartOfAccount::where('name','Cash In Hand')->where('parent_name','Cash & Cash Equivalent')->first();
      $data = Transaction::where('created_at','LIKE','%'.$date.'%')->where('chart_of_account_id',$cash['id'])->get();
      $debit = 0 ; $credit = 0;
      foreach ($data as $value){
          if($value->debit == 0){
              $credit = $credit + $value->credit;
          }else{
              $debit  = $debit + $value->debit;
          }
      }
      $netBalance = $debit - $credit;
      return $netBalance;
  }
  public function bank(){
      return ChartOfAccount::where('parent_name','Cash At Bank')->get();
  }
  public function mobileBank(){
      return ChartOfAccount::where('parent_name','Cash At Mobile Bank')->get();
  }
}
