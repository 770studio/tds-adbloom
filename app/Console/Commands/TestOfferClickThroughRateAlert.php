<?php

namespace App\Console\Commands;

use App\Models\Infrastructure\PrepareQueryBuilderWhere;
use App\Services\StatsAlerts\StatsAlertsInventoryService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestOfferClickThroughRateAlert extends Command
{
    public const CLICK_THROUGH_MIN_THREASHOLD = 2;
    public const CLICK_THROUGH_MIN_PERCENT_THREASHOLD = 50;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:alert2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    private int $conversionsThreshold = 5;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(StatsAlertsInventoryService $service, PrepareQueryBuilderWhere $queryWhere)
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


        Log::debug("start alert2 lookup");
        $this->line("start alert2 lookup");

        $h48 = $service->ConversionClicks()
            ->where($queryWhere
                ->forThePrev24Hours('StatDateTime')
            )->get();

        $h24 = $service->ConversionClicks()
            ->where($queryWhere
                ->forTheLast24Hours('StatDateTime')
            )->get();

        $h48->each(function ($item_h48) use ($h24) {

            $item_h24 = $h24->where("Stat_offer_id", $item_h48->Stat_offer_id)->first();
            //1. do have for h48 but do not have for h24
            if (!$item_h24 && $item_h48->clickthrough > self::CLICK_THROUGH_MIN_THREASHOLD) {
                //alert
            }
            //2. do have both and do have difference
            if (($item_h24->clickthrough / $item_h48->clickthrough) * 100 >= self::CLICK_THROUGH_MIN_PERCENT_THREASHOLD) {
                //alert
            }

        });


        $this->line("finished");

    }
}
