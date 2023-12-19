<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Service\Sale\PackageOrderService;
use Modules\Order\Repositories\CommissionRepository;
use App\Models\User;
class UpdateCommissionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $commissionRepository;
    public $user;
    public $packageOrderService;
    /**
     * Create a new job instance.
     */
    public function __construct(CommissionRepository $commissionRepository,PackageOrderService $packageOrderService)
    {
        $this->commissionRepository = $commissionRepository;
        $this->packageOrderService = $packageOrderService;
    }

    public function handle(): void
    {
        $roles = ['saler','leader-sale','ctv','telesale'];
        $users = User::select('id', 'name')->whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        })->get();
        foreach($users as $user){
            $this->commissionRepository->getAllOrderInMonth($this->packageOrderService,$user);
        }
    }
}
