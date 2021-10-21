<?php

namespace App\Console\Commands\StatsAlerts;

use App\Notifications\StatsAlertNotification;
use App\Services\StatsAlerts\Period24h;
use App\Services\StatsAlerts\StatsAlertsInventoryService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use LogicException;
use Psr\Log\LoggerInterface;

class TestOfferCR extends Command
{
    public const CLICK_THROUGH_MIN_THREASHOLD = 2;
    public const CLICK_THROUGH_MIN_PERCENT_THREASHOLD = 50;
    public const CLICKS_NOISE_THREASHOLD = 20;

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
    private LoggerInterface $logger;


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

        $older_period = new Period24h($older_period);
        $recent_period = new Period24h($recent_period);

        $Older = $Service->getConversionClicksCRValue($older_period, self::CLICKS_NOISE_THREASHOLD);

        $Recent = $Service->getConversionClicksCRValue($recent_period, self::CLICKS_NOISE_THREASHOLD);


        $Older
            // ->whereNotNull('cr_value')      // if older ctr =0 then we have either zero change (when recent ctr =0) or positive change
            // ->where('cr_value', '>', 0)     // whereas we are interested in negative change only
            ->each(function ($older_item) use ($Recent, $older_period, $recent_period) {
                $recent_item = $Recent->where("Stat_offer_id", $older_item->Stat_offer_id)->first();
                //1. do have for older period but do not have for recent
                //2. do have both and do have difference

                if ($diff = $this->meetsTheAlertCondition($recent_item, $older_item)) {

                    $logAlert = sprintf("CR value of %s prs. (offer name: %s) , period: from %s to %s is greater by %s prs
                     than the value of %s prs for the same upnext 24h period from %s to %s",
                        $older_item->cr_value,
                        $older_item->Offer_name,
                        $older_period->getStartDate(), $older_period->getEndDate(), // period: from to
                        round($diff, 2), // greater by
                        $recent_item->cr_value ?? 0,
                        $recent_period->getStartDate(), $recent_period->getEndDate(), // period: from to
                    );

                    $slackAlert = sprintf("Offer: *%s* - Conversion Rate is currently: *%s* (current CR) %%, changed by *%s* (CR change) %% from the previous 24h average of *%s* (24h average CR)",
                        $older_item->Offer_name,
                        $recent_item->cr_value ?? 0,
                        round($diff, 2),
                        $older_item->cr_value
                    // $older_period->getStartDate(), $older_period->getEndDate(),
                    // $recent_period->getStartDate(), $recent_period->getEndDate(),
                    );
                    $this->line("ALERT2:" . $logAlert);
                    $this->logger->debug($logAlert, [
                        'recent period' => $recent_item,
                        'older period' => $older_item,
                    ]);

                    if ($this->option('notify')) {
                        Notification::route('slack', config('services.slack_notification.alert_incoming_webhook'))
                            ->notify(new StatsAlertNotification($slackAlert));
                    }

                }


            });


        $this->line("finished");

    }

    private function meetsTheAlertCondition(?object $recent_item, object $older_item)
    {
        //1. do have for older period but do not have for recent
        if (!$recent_item && $older_item->cr_value > self::CLICK_THROUGH_MIN_THREASHOLD) {
            return round(
                100 * ($older_item->cr_value - 0) / $older_item->cr_value,
                2
            );
        }

        //2. do have both and do have difference (negative change)
        if ($recent_item && $older_item->cr_value > $recent_item->cr_value) {
            $diff = 100 * ($older_item->cr_value - $recent_item->cr_value) / $older_item->cr_value;
            if ($diff >= self::CLICK_THROUGH_MIN_PERCENT_THREASHOLD) {
                return round($diff, 2);
            }
        }

        return false;
    }
}
