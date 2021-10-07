<?php

namespace App\Console\Commands;

use App\Services\StatsAlerts\StatsAlertsInventoryService;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestIntegrationBrokenAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:alert1';

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
    public function handle(StatsAlertsInventoryService $service)
    {
        /**
         * Конверсии по оферу по ценеле по всем партнёрам упали до 0.
         * Это значит отвалилась интеграция.
         * Проверить сначала прошлый час, если в прошлый час тоже было 0, нет alert,
         * проверить прошлые 24 час, если нули, то нет alert.
         * Если прошлый час больше 0 и болье 5 (threshold), alert.
         *
         * TODO!!!!!!!!!! индексы на таблицу
         */

        $date = CarbonImmutable::now();
        $lastHour = $date->subHour()->hour;
        $beforeLastHour = $date->subHours(2)->hour;

        /*
                     $results = $service->foraCustomDateHour(
                         $service->zeroConversionResultPartnerIndependent(true),
                         $date,
                         14

                     )->get();
                dd($results);
        */
        /*
                $resultsBeforeLastHour = $service->forTheHourBeforeLastHour(
                    $service->ConversionResultPartnerIndependent(true)
                )->cursor();

                $resultsForLast24Hours = $service->forTheLast24Hours(
                    $service->ConversionResultPartnerIndependent()
                )->cursor();*/

        // $resultsLastHour =

        $service->forTheLastHour(
            $service->ConversionResultPartnerIndependent(true, true)
        )->cursor()
            ->each(function ($alertCandidate) use ($service) //use ($resultsBeforeLastHour, $resultsForLast24Hours)
            {
                Log::debug(
                    "alert candidate with zero conversions in the last hour for all partners",
                    (array)$alertCandidate
                );

                $resultsBeforeLastHour = $service->forTheHourBeforeLastHour(
                    $service->ConversionResultPartnerIndependent(true)

                )->where([
                    ['Stat_offer_id', $alertCandidate->Stat_offer_id],
                    ['Stat_offer_url_id', $alertCandidate->Stat_offer_url_id],
                    ['Stat_goal_id', $alertCandidate->Stat_goal_id],
                ])
                    ->first();

                if ($resultsBeforeLastHour
                    && $resultsBeforeLastHour->total_conversions > $this->conversionsThreshold) {
                    Log::warning(
                        "all partners zero conversion alert (comparing to prev. hour period).. integration is broken",
                        (array)$alertCandidate
                    );

                    return 1;
                }


                $resultsForLast24Hours = $service->forTheLast24Hours(
                    $service->ConversionResultPartnerIndependent()

                )->where([
                    ['Stat_offer_id', $alertCandidate->Stat_offer_id],
                    ['Stat_offer_url_id', $alertCandidate->Stat_offer_url_id],
                    ['Stat_goal_id', $alertCandidate->Stat_goal_id],
                ])
                    ->first();

                if ($resultsForLast24Hours
                    && $resultsForLast24Hours->total_conversions > $this->conversionsThreshold) {
                    Log::warning(
                        "all partners zero conversion alert (comparing to 24h period). integration is broken",
                        (array)$alertCandidate
                    );

                    return 1;
                }


                Log::warning(
                    "filterd by one of the prev. periods.",
                    (array)$alertCandidate
                );


                return 0;


                /*

                            //compare last hour to the hour before last
                            $filtered = $resultsBeforeLastHour->filter(function($value, $key) use ($alertCandidate)
                            {
                                return $value->Stat_offer_id === $alertCandidate->Stat_offer_id
                                    && $value->Stat_offer_url_id === $alertCandidate->Stat_offer_url_id
                                    && $value->Stat_goal_id === $alertCandidate->Stat_goal_id;
                            });

                            $filtered24 = $resultsForLast24Hours->filter(function($value, $key) use ($alertCandidate)
                            {
                                return $value->Stat_offer_id === $alertCandidate->Stat_offer_id
                                    && $value->Stat_offer_url_id === $alertCandidate->Stat_offer_url_id
                                    && $value->Stat_goal_id === $alertCandidate->Stat_goal_id;
                            });

                            if(!$filtered) {
                                Log::channel('alerts')->debug(
                                    "есть разница в предыдущем часе",
                                );
                                ConversionsHourlyStat::where()
                            }


                                if($filtered)
                                {
                                    // отфильтровано по 24ч
                                    Log::channel('alerts')->warning(
                                        "отфильтровано по 24ч периоду",
                                    );
                                    return 0;
                                }
                            }

                            if(!$filtered)
                            { // не отфильтрованы по прошлому часу (в прошлом часе не было нулей)
                                //compare last hour to the last 24 hours
                                Log::channel('alerts')->warning(
                                    "отфильтровано по 24ч периоду",
                                );
                            }

                                // не отфильтрованы по 24 часам (в этом периоде не было нулей)
                            if(!$filtered) {
                                // alert
                                Log::warning(
                                    "all partners zero conversion alert. integration is broken",
                                    (array)$alertCandidate
                                );
                            }
                        });

                        $this->line('finished');
                        return 0;*/
            });
    }
}
