<?php

namespace Modules\Order\Repositories;

use App\Models\User;
use Modules\Order\app\Models\OrderPackage;
use App\Models\Commission;
use Illuminate\Support\Carbon;
class CommissionRepository
{
   public function getCommissionType($type)
    {
        return Commission::where('type',$type)->orderBy('created_at', 'desc')->paginate(5);
    }
    public function getCommissionNew($type,$amount)
    {
        return Commission::where('type',$type)->where('spend_from','<=',$amount)
        ->where('spend_to','>=',$amount)
        ->orderBy('created_at', 'desc')->first();
    }
    // khi nào kích vào dashboard hoac co khoan thanh toan moi thì tính toán lại bảng chiết khấu của sale, thêm 1 nút refresh
    // truong hop dac biet cua ctv
    // tổng doanh thu của 1 user

    public function getAllOrderInMonth(){
        // return  User::select('id', 'name', 'created_byId')
        // ->whereHas('ref_order_packages.product_service_owner')
        // ->with(['ref_order_packages.commissions_packages','ref_order_packages.historyPayment' => function ($q){
        //       $q->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        //   },
        //   'ref_order_packages' => function ($q){
        //     $q->withSum('historyPayment','amount_received');
        //   }
        //   ])
        //   ->get();

        return  User::select('id', 'name', 'created_byId')
        ->where('id',8)
        ->whereHas('ref_order_packages.product_service_owner')
        ->with([
          'ref_order_packages' => function ($q){
            $q->withSum(['historyPayment'=> function ($query){
                $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            }],
            'amount_received');
          }])
          ->get();
    }

}
