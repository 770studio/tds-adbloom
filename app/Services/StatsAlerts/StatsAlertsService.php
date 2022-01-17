<?php

namespace App\Services\StatsAlerts;

use App\Helpers\CompareObjectValueHelper;
use App\Models\Infrastructure\AlertDTO;
use App\Notifications\StatsAlertNotification;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Psr\Log\LoggerInterface;

class StatsAlertsService
{
    public const ALERT2_CLICK_THROUGH_MIN_THRESHOLD = 2;
    public const ALERT2_CLICK_THROUGH_MIN_PERCENT_THRESHOLD = 50;
    public const ALERT2_CLICKS_NOISE_THRESHOLD = 90;

    public const AlERT3_MIN_CLICKS_REQUIRED = 50;
    public const ALERT3_MIN_CONVERSIONS_REQUIRED = 1;

    private LoggerInterface $logger;
    private StatsAlertsInventoryService $inventory;
    private ?bool $notify = null;
    private Collection $alerts;

    public function __construct(StatsAlertsInventoryService $inventory)
    {
        $this->inventory = $inventory;
        $this->logger = Log::channel('stats_alerts');
        $this->alerts = collect();

    }

    public function testAlert2(FlexPeriod $recent_period, FlexPeriod $older_period): void
    {

        dump("start alert2 lookup");
        $this->logger->debug("alert2 lookup fired");
        dump("periods:", $older_period->toArray(), $recent_period->toArray());

        $this->logger->debug("periods decoded:", [
            'recent' => $recent_period->getDateRange(),
            'older' => $older_period->getDateRange(),
        ]);

        $Older = $this->inventory->getConversionClicksCRValue($older_period);
        //dd($Older);
        $Recent = $this->inventory->getConversionClicksCRValue($recent_period);

        $Older->each(function ($older_item) use ($Recent, $older_period, $recent_period) {
            $recent_item = $Recent->where("Stat_offer_id", $older_item->Stat_offer_id)->first();

            if (!$recent_item || $recent_item->clicks < self::ALERT2_CLICKS_NOISE_THRESHOLD ||
                $older_item->clicks < self::ALERT2_CLICKS_NOISE_THRESHOLD) {
                return true; // one of the periods is below noise threshold  continue to next one
            }

            $comparator = (new CompareObjectValueHelper('cr_value', self::ALERT2_CLICK_THROUGH_MIN_PERCENT_THRESHOLD));
            // check if we have DOWN (classic older period is greater than recent)
            //TODO refactor as following looks like shit

            if ($comparator->compareBothWays($recent_item, $older_item)->hasDiff()) {
                $this->addAlert(
                    $comparator->toAlert()
                        ->setPeriods($older_period, $recent_period)
                        ->setRecentClicks($recent_item->clicks)
                );

                $this->logger->debug('alert is about to fire',
                    [
                        'recent_period' => $recent_item,
                        'older_period' => $older_item,
                    ]);
            }

        });


        $this->alerts->sortBy('direction')
            ->each(function ($alertDTO) {
                $this->slackAlert(
                    sprintf("%s *%s* - Conversion Rate: *%s* %% *%s* by *%s* %% from prior day average of *%s* %% with *%s* clicks",
                        $alertDTO->direction === "UP" ? ":arrow_up:" : ":arrow_down:",
                        $alertDTO->offer_name,
                        $alertDTO->recent_item_prs_value,
                        $alertDTO->direction,
                        $alertDTO->diff_prs,
                        $alertDTO->older_item_prs_value,
                        $alertDTO->recent_clicks
                    )
                );
            });

        dump("finished");
    }

    public function testAlert3(FlexPeriod $recent_period, FlexPeriod $older_period): void
    {
        dump("start alert3 lookup");
        $this->logger->debug("alert3 lookup fired");

        $this->logger->debug("periods decoded:", [
            'recent' => $recent_period->getDateRange(),
            'older' => $older_period->getDateRange(),
        ]);


        $Recent = $this->inventory->getConversionClicksCRValue($recent_period, function (Builder $query) {
            return $query->havingRaw('clicks >= ? or conversions >= ?', [self::AlERT3_MIN_CLICKS_REQUIRED,
                    self::ALERT3_MIN_CONVERSIONS_REQUIRED]
            );
        });

        $Older = $this->inventory->getConversionClicksCRValueWithNoActivity($older_period, $Recent->pluck('Stat_offer_id'));
        if ($Older->isEmpty()) {
            $this->logger->debug("no results for the test within older period");
            dump("no results for the test within older period");
            return;
        }


        $Older->each(function ($older_item) use ($Recent) {
            $recent_item = $Recent->where('Stat_offer_id', $older_item->Stat_offer_id)->first();
            $this->logger->debug('alert is about to fire',
                [
                    'recent_period' => $recent_item,
                    'older_period' => $older_item,
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
        dump("ALERT:" . $slackText);
        $this->logger->debug("ALERT:" . $slackText);

        if ($this->notify) {
            //TODO can be refactored to slack log channel
            Notification::route('slack', config('services.slack_notification.alert_incoming_webhook'))
                ->notify(new StatsAlertNotification($slackText));
        }


    }

    public function notify(?bool $notify): self
    {
        $this->notify = $notify;
        return $this;
    }

    private function addAlert(AlertDTO $alertDTO): void
    {
        $this->alerts->push($alertDTO);
    }
}
