<?php

namespace App\Listeners;

use App\Events\OrderPost;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailToOrderUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderPost  $event
     * @return void
     */
    public function handle(OrderPost $event)
    {
        $event->order->express_type = $event->express_type;
        $event->order->express_no = $event->express_no;
        $event->order->status = 3;
        $event->order->save();

        Mail::to($event->order->user)->queue(new \App\Mail\OrderPost($event->order));
    }
}
