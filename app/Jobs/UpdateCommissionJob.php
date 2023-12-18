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
use App\Models\Commission;
class UpdateCommissionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $commission;
    public $user;
    /**
     * Create a new job instance.
     */
    public function __construct(Commission $commission)
    {
        $this->commission = $commission;
    }


    public function handle(): void
    {

    }
}
