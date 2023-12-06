<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Order\app\Models\OrderPackage;

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
    public function handle(): void
    {
        $order = OrderPackage::with('historyPayment')->find($this->order->id);
        if($order){
            if($order->payment_check){
                // đã thanh toán và kế toán đã duyệt toàn bộ => dùng đếm
            }
        }
    }
}
