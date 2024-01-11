<?php

namespace Modules\Order\Repositories;

use App\Models\User;
use Modules\Order\app\Models\OrderPackage;
use App\Models\Commission;
use App\Models\CommissionType;
use App\Service\Sale\PackageOrderService;
use Illuminate\Support\Carbon;
use Modules\Order\app\Models\commissionsPackage;
class CommissionRepository
{
   public function getCommissionType($type)
    {
        return Commission::where('type',$type)->orderBy('created_at', 'desc')->paginate(5);
    }
    public function checkCommission($type,$user_type,$amount)
    {
        return Commission::where('commission_type_id',$type)
        ->where('user_type_id',$user_type)
        ->where('spend_from','<',$amount)
        ->where('spend_to','>=',$amount)
        ->orderBy('created_at', 'desc')->first();
    }
    // khi nào kích vào dashboard hoac co khoan thanh toan moi thì tính toán lại bảng chiết khấu của sale, thêm 1 nút refresh
    // truong hop dac biet cua ctv
    // tổng doanh thu của 1 user
    public function getAmountCommission($order,$user,$total){
        $commission_amount = 0;
        // if($order->customer_resources == "ctv" && $order->ref_id == $user->id){
        //     $commission_amount = $order->history_payment_sum_amount_received*($commision->commission - $commision->discount_form_sale)/100;
        // }elseif($order->customer_resources == "ctv" && $order->to_id == $user->id){
        //     $commission_amount = $order->history_payment_sum_amount_received*($commision->commission - $commision->discount_form_manager_sale)/100;
        // }
        // else{
        //     $commission_amount = $order->history_payment_sum_amount_received*$commision->commission/100;
        // }
        // return $commission_amount;

        if($order->ref_id !=null && $order->to_id != null && $order->customer_resources == "tele"){
            if($order->ref_id == $user->id){
                $commission_amount = $order->history_payment_sum_amount_received;
            }

        }elseif($order->ref_id !=null && $order->to_id != null && $order->customer_resources == "ctv"){

        }elseif($order->ref_id !=null && $order->to_id != null && $order->customer_resources == "private"){

        }elseif($order->ref_id !=null && $order->ref_id == null && $order->customer_resources == "tele"){

        }elseif($order->ref_id !=null && $order->ref_id == null && $order->customer_resources == "private"){

        }elseif($order->ref_id !=null && $order->ref_id == null && $order->customer_resources == "ctv"){

        }elseif($order->ref_id ==null && $order->ref_id != null && $order->customer_resources == "tele"){

        }elseif($order->ref_id ==null && $order->ref_id != null && $order->customer_resources == "private"){

        }elseif($order->ref_id ==null && $order->ref_id != null && $order->customer_resources == "ctv"){

        }elseif($order->ref_id ==null && $order->ref_id == null && $order->customer_resources == "ctv"){
        }

        
    }
    public function getAllOrderInMonth(PackageOrderService $packageOrderService,$user){

        // check them truong hop price_percent dat bn % tong don hang
        $orders = $packageOrderService->getOrderInMonth($user);
        $total = $orders->sum('history_payment_sum_amount_received');
        $user_role = $user->roles[0]->name;
        // $commision = $this->checkCommission($user_role,$total);

        // if($commision){
        // update hoa hong
            foreach($orders as $order){
                $commissionsPackage = $user->commission()->where('order_package_id',$order->id)
                ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->first();
                if($commissionsPackage == null){
                    // tao moi
                    $commissionsPackage = new commissionsPackage;
                }
                $commissionsPackage->total_order = $total;
                $commissionsPackage->amount_received = $order->history_payment_sum_amount_received;
                $commissionsPackage->commission_amount = $this->getAmountCommission($order,$user,$total);
                // $commissionsPackage->commission_percentage = $commision->commission;
                // $commissionsPackage->level_revenue = $commision->level_revenue;
                // $commissionsPackage->order_package_id = $order->id;
                // $commissionsPackage->commissions_id = $commision->id;
                // $commissionsPackage->user_id = $user->id;

                $commissionsPackage->save();
            }
        // }
        return $orders;

    }
    public function listCommissionUser($user,$filters){
        $user_filter = $user->whereHas('commission', function ($query) use ($filters){
            $query->filterTime($filters);
        })
        ->withSum('commission','commission_amount')
        ->withSum('commission','amount_received')
        ->withSum('commission','commission_paid')
        ->withCount(['ref_order_packages as count_order_notdecline' => function ($query) use ($filters) {
            $query->where('status','!=','decline');
            $query->filterTime($filters);
        }])
        ->withCount(['ref_order_packages as count_order_decline' => function ($query) use ($filters) {
            $query->where('status','==','decline');
            $query->filterTime($filters);
        }])->orderBy('commission_sum_commission_amount', 'desc');
        return $user_filter;
    }

}
