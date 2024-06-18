<?php

namespace App\Listeners;

use App\Events\OrderStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateProductQuantity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderStatus $event): void
    {

        $order = $event->order;
        foreach ($order->orderDetails as $orderDetail) {
            $product = $orderDetail->product;
            $product->quantity -= $orderDetail->quantity;
            $product->save();
        }
    }
}
