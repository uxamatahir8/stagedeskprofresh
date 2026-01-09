<?php

namespace App\Events;

use App\Models\BookingRequest;
use App\Models\Company;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;
    public $company;

    /**
     * Create a new event instance.
     */
    public function __construct(BookingRequest $booking, Company $company)
    {
        //
        $this->booking = $booking;
        $this->company = $company;
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
