<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductCreated implements ShouldBroadcast
{

    public $product;

    use Dispatchable, InteractsWithSockets, SerializesModels;

   
    public function __construct($product)
    {
        $this->product = $product;
    }

  
    public function broadcastOn()
    {
        return new Channel('product-created');
    }

    public function broadcastWith()
    {
        return ['product' => $this->product];   
    }
}
