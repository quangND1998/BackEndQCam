<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Service\Sale\PackageOrderService;
use Modules\Order\Repositories\CommissionRepository;
use App\Models\User;
use Modules\Order\app\Models\OrderPackage;

class UpdateCommissionUser implements ShouldQueue
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
    public function checkUserInOrder(){
      return  User::with('product_service_owners','historyPayments','commission')
        ->where('id',$this->order->ref_id)
        ->orwhere('id',$this->order->to_id)
        ->orwhere('id',$this->order->customer_resources_id)->get();
    }
    public function handle(CommissionRepository $commissionRepository,PackageOrderService $packageOrderService): void
    {
        $users = $this->checkUserInOrder();
        foreach($users as $user){
            $commissionRepository->getAllOrderInMonth($packageOrderService,$user);
        }
    }
}
