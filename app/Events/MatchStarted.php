<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchStarted implements ShouldBroadcast
{
    public $match;

    public function __construct($match)
    {
        $this->match = $match;
    }

    public function broadcastOn()
    {
        return new Channel('match.' . $this->match->id);
    }
}

