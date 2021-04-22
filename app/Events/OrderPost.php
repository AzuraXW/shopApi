<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPost
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $order = null;
    public $express_no = null;
    public $express_type = null;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($order, $express_no, $express_type)
    {
        //
        $this->order = $order;
        $this->express_no = $express_no;
        $this->express_type = $express_type;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
