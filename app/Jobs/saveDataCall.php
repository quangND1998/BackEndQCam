<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\CallCenter\Repositories\CGVTeleRepository;

class saveDataCall implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $cgvTeleRepository;
    public $sipCallId;
    public $distributeCallIds;

    public function __construct($sipCallId, $distributeCallIds)
    {
        $this->sipCallId = $sipCallId;
        $this->distributeCallIds = $distributeCallIds;
    }

    /**
     * Execute the job.
     */
    public function handle(CGVTeleRepository $cgvTeleRepository): void
    {
        $cgvTeleRepository->getCallDetail($this->sipCallId, $this->distributeCallIds);
    }
}
