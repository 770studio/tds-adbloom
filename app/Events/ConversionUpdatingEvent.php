<?php

namespace App\Events;

use App\Models\Conversion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;


class ConversionUpdatingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Conversion $conversion;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Conversion $conversion)
    {
        $this->conversion = $conversion;
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
