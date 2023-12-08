<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Order\app\Models\OrderPackage;
use Modules\Customer\app\Models\ProductServiceOwner;
class OrderPackageEndTimeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $order;
    /**
     * Create a new job instance.
     */
    public function __construct(OrderPackage $order)
    {
        $this->order = $order;
    }
    /**
     * Execute the job.
     */
    public function changeStatus($order,$state_new){
        $order->status = $state_new;
        $order->reason = "hết thời gian chờ";
        $order->save();

        $product_service_owner = $order->product_service_owner;
        if($product_service_owner){
            foreach($product_service_owner->trees as $tree){
                $tree->product_service_owner_id = null;
                $tree->save();
            }
            $product_service_owner->delete();
        }
    }
    public function handle(): void
    {
       
        $order = OrderPackage::with('historyPayment')->find($this->order->id);
        if($order->time_expried == Carbon::now()){
            if($order){
                if($order->price_percent == 0){
                    $this->changeStatus($order,"decline");
                    // chuyen sang trang thai huy, xoa cac hop dong lien quan
                }
                if($order->price_percent > 0 && $order->payment_check == false){
                    $this->changeStatus($order,"pending");
                    // chuyen sang trang thai cho duyet, xoa cac hop dong lien quan
                }
                if($order->payment_check && $order->price_percent >= $order->grand_total){
                    // đã thanh toán và kế toán đã duyệt toàn bộ => dừng đếm
                }
    
            }
        }
      
    }
}
