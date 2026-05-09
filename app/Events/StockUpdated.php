<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $inventory;
    public $message;

    public function __construct($inventory, $message = '')
    {
        $this->inventory = $inventory->load(['product', 'branch']);
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('inventory-updates'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'stock.updated';
    }
}
