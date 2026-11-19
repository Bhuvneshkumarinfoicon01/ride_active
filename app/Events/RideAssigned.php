<?php

namespace App\Events;

use App\Models\Ride;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RideAssigned implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(public Ride $ride)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('driver.' . $this->ride->driver_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ride.assigned';
    }

    public function broadcastWith(): array
    {
        return [
            'ride_id' => $this->ride->id,
            'driver_id' => $this->ride->driver_id,
            'pickup_location' => $this->ride->pickup_location,
            'drop_location' => $this->ride->drop_location,
            'status' => $this->ride->status,
            'message' => 'You have received a new ride!',
        ];
    }
}