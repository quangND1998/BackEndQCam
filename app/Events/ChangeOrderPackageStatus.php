<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderPackage;

class ChangeOrderPackageStatus
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $order, $status;
    /**
     * Create a new event instance.
     */
    public function __construct(OrderPackage $order , $status)
    {
        $this->order = $order;
        $this->status = $status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
