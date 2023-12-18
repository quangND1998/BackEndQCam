<?php

namespace Modules\Order\Repositories;

use App\Models\User;
use Modules\Order\app\Models\OrderPackage;
use App\Models\Commission;
use App\Service\Sale\PackageOrderService;
use Illuminate\Support\Carbon;
use Modules\Order\app\Models\commissionsPackage;
class CommissionRepository
{
   public function getCommissionType($type)
    {
        return Commission::where('type',$type)->orderBy('created_at', 'desc')->paginate(5);
    }
    public function checkCommission($type,$amount)
    {
        return Commission::where('type',$type)
        ->where('spend_from','<',$amount)
        ->where('spend_to','>=',$amount)
        ->orderBy('created_at', 'desc')->first();
    }
    // khi nào kích vào dashboard hoac co khoan thanh toan moi thì tính toán lại bảng chiết khấu của sale, thêm 1 nút refresh
    // truong hop dac biet cua ctv
    // tổng doanh thu của 1 user
    public function getAmountCommission($order,$user,$commision){
        $commission_amount = 0;
        // if($order->ref_id == $user->id){
        //     $commission_amount += $order->history_payment_sum_amount_received*$commision->commission;
        // }
        // if($order->to_id == $user->id){
        //     $commission_amount += $order->history_payment_sum_amount_received*$commision->commission;
        // }
        if($order->customer_resources != null && $order->customer_resources_id == $user->id){
            // neu customer_resources == sale thi ++
            
        }

    }
    public function getAllOrderInMonth(PackageOrderService $packageOrderService,$user){
        $orders = $packageOrderService->getOrderInMonth($user);
        $total = $orders->sum('history_payment_sum_amount_received');

        $user_role = $user->roles[0]->name;
        $commision = $this->checkCommission($user_role,$total);

        if($commision){
        // update hoa hong
            foreach($orders as $order){
                if($order->commissions_packages == null){
                    // tao moi
                    $commissionsPackage = new commissionsPackage;
                }else{
                    // update
                    $commissionsPackage = $order->commissions_packages;
                }
                $commissionsPackage->commission_percentage = $commision->commission;
                $commissionsPackage->level_revenue = $commision->level_revenue;
                $commissionsPackage->order_package_id = $order->id;
                $commissionsPackage->commissions_id = $commision->id;
                $commissionsPackage->save();
            }
        }

        return $orders;
    }

}
