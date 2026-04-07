<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SalesTeamDeleted implements ShouldBroadcast
{

    public $salesTeam;

    use Dispatchable, InteractsWithSockets, SerializesModels;

    
    public function __construct($salesTeam)
    {
        $this->salesTeam = $salesTeam;
    }


    public function broadcastOn()
    {
        return new Channel('sales-team-deleted');
    }

    public function broadcastWith()
    {
        return ['salesTeamDeleted' => $this->salesTeam];   
    }
}
