<?php

namespace App\Listeners;

use App\Events\ConversionUpdatingEvent;

class RevShareUpdateListener //implements ShouldQueue
{
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public string $queue = 'listeners';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param ConversionUpdatingEvent $event
     * @return bool
     */
    public function handle(ConversionUpdatingEvent $event)
    {
        $conv = $event->conversion;
        if(!$conv->wasRecentlyCreated
            && !in_array("Stat_payout", $conv->getChanges())
        ) {
            return true ;
        }

        if(!$partner = $conv->Partner) {
            return true;
        }

        // either it's a creating or updating with Stat_payout being changed
        // and partner is not null
        $conv->user_payout = ($partner->percentage * $conv->Stat_payout)/100;
        $conv->user_points = floor($partner->points_multiplier * $conv->user_payout);
        return true;

    }
}
