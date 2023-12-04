<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MakeSalerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $leader, $data;
    /**
     * Create a new job instance.
     */
    public function __construct(User $leader, $data)
    {
        $this->leader = $leader;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!User::where('email', 'sale' . $this->data . '@cammattroi.com')->exists()) {
            $saler = User::create([
                'name' => 'sale' . $this->data,
                'email' => 'sale' . $this->data . '@cammattroi.com',
                'password' => bcrypt('cammattroi'),
                'created_byId' => $this->leader->id,
            ]);
            $saler->assignRole('saler');
        }
    }
}
