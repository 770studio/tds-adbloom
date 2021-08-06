<?php

namespace App\Listeners;

use App\Events\PartnerUpdatedEvent;

class UpdatePartnerConversionListener //implements ShouldQueue
{
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public ?string $queue = 'listeners';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(PartnerUpdatedEvent $event)
    {

    }

    /**
     * Handle the event.
     *
     * @param PartnerUpdatedEvent $event
     * @return void
     */
    public function handle(PartnerUpdatedEvent $event)
    {
        if (!in_array("rev_share", $event->partner->getChanges())) return;
        //  $event->partner->conversions->each(function)
    }
}
