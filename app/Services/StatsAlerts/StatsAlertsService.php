<?php

namespace App\Services\StatsAlerts;

use App\Notifications\StatsAlertNotification;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Psr\Log\LoggerInterface;

class StatsAlertsService
{
    public const MIN_CLICKS_REQUIRED = 50;
    public const MIN_CONVERSIONS_REQUIRED = 1;

    private LoggerInterface $logger;
    private StatsAlertsInventoryService $inventory;

    public function __construct(StatsAlertsInventoryService $inventory)
    {
        $this->inventory = $inventory;
        $this->logger = Log::channel('stats_alerts');

    }

    public function testAlert3(FlexPeriod $recent_period, FlexPeriod $older_period)
    {
        dump("start alert3 lookup");
        $this->logger->debug("alert3 lookup fired");

        $this->logger->debug("periods decoded:", [
            'recent' => $recent_period->getDateRange(),
            'older' => $older_period->getDateRange(),
        ]);

        $Older = $this->inventory->getConversionClicksCRValueWithNoActivity($older_period);
        if ($Older->isEmpty()) {
            // TODO refactor decorate ?
            $this->logger->debug("no results for the test within older period");
            dump("no results for the test within older period");
            return 0;
        }

        $Recent = $this->inventory->getConversionClicksCRValue($recent_period, function (Builder $query) use ($Older) {
            return $query->whereIn('Stat_offer_id', $Older->pluck('Stat_offer_id'))
                ->havingRaw('clicks >= ? or conversions >= ?', [self::MIN_CLICKS_REQUIRED,
                        self::MIN_CONVERSIONS_REQUIRED]
                );
        });

        if ($Recent->isEmpty()) {
            // TODO refactor decorate ?
            $this->logger->debug("no results for the test within recent period");
            dump("no results for the test within recent period");
            return 0;
        }

        $Recent->each(function ($recent_item) use ($Older, $older_period, $recent_period) {

            $this->logger->debug('alert is about to fire',
                [
                    'recent_period' => $recent_item,
                    'older_period' => $Older->where('Stat_offer_id', $recent_item->Stat_offer_id)->first(),
                ]);

            $this->slackAlert(
                sprintf("Campaign Activated: *%s* has received *%d* clicks and *%d* conversions in the last day, the first time in the previous 30 days",
                    $recent_item->Offer_name,
                    $recent_item->clicks,
                    $recent_item->conversions,
                )
            );


        });

        dump("finished");
    }

    public function slackAlert(string $slackText): void
    {


        dump("ALERT3:" . $slackText);
        $this->logger->debug("ALERT3:" . $slackText);

        if ($this->option('notify')) {
            //TODO can be refactored to slack log channel
            Notification::route('slack', config('services.slack_notification.alert_incoming_webhook'))
                ->notify(new StatsAlertNotification($slackText));
        }


    }
}
