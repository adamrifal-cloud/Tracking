<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderLocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $orderId;
    public $driverName;
    public $status;
    public $latitude;
    public $longitude;
    public $stage;
    public $eta;
    public $updatedAt;

    /**
     * Create a new event instance.
     */
    public function __construct($orderId, $driverName, $status, $latitude, $longitude, $stage, $eta, $updatedAt)
    {
        $this->orderId = $orderId;
        $this->driverName = $driverName;
        $this->status = $status;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->stage = $stage;
        $this->eta = $eta;
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('order.' . $this->orderId),
        ];
    }

    /**
     * Get the broadcast event name.
     */
    public function broadcastAs(): string
    {
        return 'OrderLocationUpdated';
    }
}
