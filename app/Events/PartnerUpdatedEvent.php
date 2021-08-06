<?php

namespace App\Events;

use App\Models\Partner;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;


class PartnerUpdatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Partner $partner;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Partner $partner)
    {
        Log::debug('PARTNERRRRRRRRRRRR');
        $this->partner = $partner;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
