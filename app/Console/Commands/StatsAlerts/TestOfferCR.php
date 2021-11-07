<?php

namespace App\Console\Commands\StatsAlerts;

use App\Helpers\CompareObjectValueHelper;
use App\Notifications\StatsAlertNotification;
use App\Services\StatsAlerts\Period24h;
use App\Services\StatsAlerts\StatsAlertsInventoryService;
use Illuminate\Console\Command;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use LogicException;

final class TestOfferCR extends Command
{
    public const CLICK_THROUGH_MIN_THRESHOLD = 2;
    public const CLICK_THROUGH_MIN_PERCENT_THRESHOLD = 50;
    public const CLICKS_NOISE_THRESHOLD = 20;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statstests:alert2 {--older=} {--recent=} {--notify}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    private Logger $logger;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->logger = Log::channel('stats_alerts');
    }

    /*    public function line($string, $style = null, $verbosity = null)
        {
            $this->logger->debug($string);
            parent::line($string, $style, $verbosity);
        }*/
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(StatsAlertsInventoryService $Service)
    {
        /**
         * we have an offer
         * the offer has a conversion rate
         * conversioni rate = clicks/conversions
         * 10 clicks/1 convesion = 10% conversion rate
         * lets say each day, offer conversion rate is average 10%
         * on day 12
         * conversion rate is 4%
         * on day 13, conversion rate is 2%
         * conversion rate has changed by more then 50% in 24 hour period
         * we need an alert
         *
         */

        $this->line("start alert2 lookup");
        $this->logger->debug("alert2 lookup fired");

        // but we also might need to compare a custom arbitrary period
        // TODO refactor somehow
        if ($this->option('older') !== null) {
            $older_period = (int)$this->option('older');
            $recent_period = (int)$this->option('recent');

            if (!$recent_period) {
                $recent_period = $older_period - 1;
                if ($recent_period < 0) {
                    throw new LogicException('can not use a future period');
                }
            }
        } else {
            // by default we compare lastDay and dayBeforelastDay
            $older_period = 'dayBeforelastDay';
            $recent_period = 'lastDay';
        }
        dump($older_period, $recent_period);

        $this->logger->debug("periods:", [
            'recent' => $recent_period,
            'older' => $older_period,
        ]);

        $older_period = new Period24h($older_period);
        $recent_period = new Period24h($recent_period);

        $this->logger->debug("periods decoded:", [
            'recent' => $recent_period->getDateRange(),
            'older' => $older_period->getDateRange(),
        ]);

        $Older = $Service->getConversionClicksCRValue($older_period);
        $Recent = $Service->getConversionClicksCRValue($recent_period);

        $Older->each(function ($older_item) use ($Recent, $older_period, $recent_period) {
            $recent_item = $Recent->where("Stat_offer_id", $older_item->Stat_offer_id)->first();

            if ($recent_item->clicks < self::CLICKS_NOISE_THRESHOLD ||
                $older_item->clicks < self::CLICKS_NOISE_THRESHOLD) {
                return true; // continue as one of the periods is below noise threshold
            }

            $comparator = (new CompareObjectValueHelper('cr_value', self::CLICK_THROUGH_MIN_PERCENT_THRESHOLD));
            // check if we have DOWN (classic older period is greater than recent)
            //TODO refactor as following looks like shit
            if ($diff = $comparator->compare($recent_item, $older_item)) {
                $direction = 'DOWN';
            } elseif ($diff = $comparator->compare($older_item, $recent_item)) {
                $direction = 'UP';
            }

            if ($diff) {
                $this->logger->debug('alert is about to fire',
                    [
                        'recent_period' => $recent_item,
                        'older_period' => $older_item,
                    ]);

                $alertDto = AlertDTO::fromArray([
                    'direction' => $direction,
                    'diff_prs' => $diff,
                    'recent_period' => $recent_period,
                    'older_period' => $older_period,
                    'recent_item_prs_value' => $recent_item->cr_value,
                    'older_item_prs_value' => $older_item->cr_value,
                    'offer_name' => $older_item->Offer_name
                ]);
                $this->stat_alert($alertDto);
            }


        });
        $this->line("finished");

    }

    public function stat_alert(AlertDTO $alertDTO): void
    {

        $logAlert = sprintf("CR value of %s prs. (offer name: %s) , period: from %s to %s is greater by %s prs
                     than the value of %s prs for the same daily period from %s to %s",
            $alertDTO->older_item_prs_value,
            $alertDTO->offer_name,
            $alertDTO->older_period->getStartDate(), $alertDTO->older_period->getEndDate(), // period: from to
            $alertDTO->diff_prs, // greater by
            $alertDTO->recent_item_prs_value,
            $alertDTO->recent_period->getStartDate(), $alertDTO->recent_period->getEndDate(), // period: from to
        );


        //CR is %(current cr), (DOWN/UP) by %(CR % Change) from prior day average of %(yesterday CR)
        $slackAlert = sprintf("Offer: *%s* - CR is *%s*(current cr), (*%s*) by *%s*(CR %% Change) from prior day average of *%s*(yesterday CR)",
            $alertDTO->offer_name,
            $alertDTO->recent_item_prs_value,
            $alertDTO->direction,
            $alertDTO->diff_prs,
            $alertDTO->older_item_prs_value
        );

        $this->line("ALERT2:" . $logAlert);
        $this->logger->debug($logAlert);

        if ($this->option('notify')) {
            Notification::route('slack', config('services.slack_notification.alert_incoming_webhook'))
                ->notify(new StatsAlertNotification($slackAlert));
        }


    }


}
