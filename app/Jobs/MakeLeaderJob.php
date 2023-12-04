<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MakeLeaderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!User::where('email', 'leader' . $this->data . '@cammattroi.com')->exists()) {
            $leader = User::create([
                'name' => 'leader' . $this->data,
                'email' => 'leader' . $this->data . '@cammattroi.com',
                'password' => bcrypt('cammattroi')

            ]);
            $leader->assignRole('leader-sale');
            if ($leader) {
                if ($this->data == 1) {
                    for ($i = 1 * $this->data; $i <= 20 * $this->data; $i++) {
                        dispatch(new MakeSalerJob($leader, $i));
                    }
                } else {
                    for ($i = 1 * $this->data * 10 + 1; $i <= 20 * $this->data; $i++) {
                        dispatch(new MakeSalerJob($leader, $i));
                    }
                }
            }
        } else {
            $leader =  User::where('email', 'leader' . $this->data . '@cammattroi.com')->exists();
            if ($leader) {
                if ($this->data == 1) {
                    for ($i = 1 * $this->data; $i <= 20 * $this->data; $i++) {
                        dispatch(new MakeSalerJob($leader, $i));
                    }
                } else {
                    for ($i = 1 * $this->data * 10 + 1; $i <= 20 * $this->data; $i++) {
                        dispatch(new MakeSalerJob($leader, $i));
                    }
                }
            }
        }
    }
}
