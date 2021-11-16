<?php

namespace App\Console\Commands\StatsAlerts;

use App\Models\Infrastructure\AlertDTO;
use App\Notifications\StatsAlertNotification;
use App\Services\StatsAlerts\FlexPeriod;
use App\Services\StatsAlerts\StatsAlertsInventoryService;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

/**
 * i want a alert that fires when a campaign gets 50 clicks and/or 1+ conversion
 * when it never had activity before in the last 30 days.
 * "Campaign Activated: %OfferName has received $$ clicks and $$ conversions in the last 24 hours, the first time in the previous 30 days
 */
final class TestOfferActivityCommand extends Command
{
    public const MIN_CLICKS_REQUIRED = 50;
    public const MIN_CONVERSIONS_REQUIRED = 1;


    /**
     * The name and signature of the console command.
     *
     * @var string
     *
     *
     */
    protected $signature = 'statstests:alert3 {--notify}';

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


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(StatsAlertsInventoryService $Service): int
    {
        $this->line("start alert3 lookup");
        $this->logger->debug("alert3 lookup fired");

        //first period is starting 30 days ago and ending day before yesterday end of the day
        $older_period = new FlexPeriod('last30d');
        //second period is yesterday
        $recent_period = new FlexPeriod(1);

        $this->logger->debug("periods decoded:", [
            'recent' => $recent_period->getDateRange(),
            'older' => $older_period->getDateRange(),
        ]);

        $Older = $Service->getConversionClicksCRValueWithNoActivity($older_period);
        if ($Older->isEmpty()) {
            // TODO refactor decorate ?
            $this->logger->debug("no results for the test within older period");
            $this->line("no results for the test within older period");
            return 0;
        }
        //dd($Older->pluck('Stat_offer_id'));
        $Recent = $Service->getConversionClicksCRValue($recent_period, function (Builder $query) use ($Older) {
            return $query->whereIn('Stat_offer_id', $Older->pluck('Stat_offer_id'))
                ->havingRaw(sprintf("clicks >= %d or conversions >= %d",
                    self::MIN_CLICKS_REQUIRED,
                    self::MIN_CONVERSIONS_REQUIRED
                ));
        });

        if ($Recent->isEmpty()) {
            // TODO refactor decorate ?
            $this->logger->debug("no results for the test within recent period");
            $this->line("no results for the test within recent period");
            return 0;
        }

        $Recent->each(function ($recent_item) use ($Older, $older_period, $recent_period) {

            $this->logger->debug('alert is about to fire',
                [
                    'recent_period' => $recent_item,
                    'older_period' => $Older->where('Stat_offer_id', $recent_item->Stat_offer_id)->first(),
                ]);

            $alertDto = AlertDTO::fromArray([
                'direction' => "UP",
                'recent_period' => $recent_period,
                'older_period' => $older_period,
                'offer_name' => $recent_item->Offer_name,
                'recent_clicks' => $recent_item->clicks,
                'recent_conversions' => $recent_item->conversions
            ]);

            $this->stat_alert($alertDto);


        });

        $this->line("finished");
        return 1;
    }

    public function stat_alert(AlertDTO $alertDTO): void
    {
        $slackAlert = sprintf("Campaign Activated: *%s* has received *%d* clicks and *%d* conversions in the last day, the first time in the previous 30 days",
            $alertDTO->offer_name,
            $alertDTO->recent_clicks,
            $alertDTO->recent_conversions,
        );

        $this->line("ALERT3:" . $slackAlert);
        $this->logger->debug("ALERT3:" . $slackAlert);

        if ($this->option('notify')) {
            //TODO can be refactored to slack log channel
            Notification::route('slack', config('services.slack_notification.alert_incoming_webhook'))
                ->notify(new StatsAlertNotification($slackAlert));
        }


    }


}
