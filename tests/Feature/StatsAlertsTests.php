<?php

namespace Tests\Feature;

use App\Models\ConversionsHourlyStat;
use App\Services\TuneAPI\TuneAPIService;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Tune\NetworkApi;
use Tune\Utils\HttpQueryBuilder;
use Tune\Utils\Operator;

class StatsAlertsTests extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_command_is_collable()
    {


        $api = $this->app->make(NetworkApi::class);

        $tuneAPIService = $this->app->make(TuneAPIService::class);

        // $tuneAPIService->getConversionsHourlyStats(1);
        $page = 1;
        $result = $api
            ->report()
            ->getStats(function (HttpQueryBuilder $builder) use ($page) {

                $builder->setFields(ConversionsHourlyStat::getFields());

                /* filters[Stat.hour][conditional]=EQUAL_TO&filters[Stat.hour][values]=17&filters[Stat
    .date][conditional]=EQUAL_TO&filters[Stat.date][values]=2021-03-24&
    */
                $builder->addFilter('Stat.hour', 17
                    , null, Operator::EQUAL_TO);
                $builder->addFilter('Stat.date', "2021-03-24"
                    , null, Operator::EQUAL_TO);
                return $builder;
                //$builder->->setPage($page);
            }, /* Request options */ []);

        dd(4444, $result);


        Artisan::call('conversions:collectHourlyStats');


    }

}
