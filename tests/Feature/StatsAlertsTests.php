<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class StatsAlertsTests extends TestCase
{
    use WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_parse_data()
    {

        $this->withoutMiddleware(); // dont work at all!!!

        // TuneAPIGetConversionPageJob::dispatch(1, []);
        //Artisan::call('conversions:update');
        Artisan::call('conversions:collectHourlyStats --stat_date=2021-03-24 --stat_hour=11');
        $this->assertDatabaseCount('conversions_hourly_stats', 94);


    }

    public function test_example_inventory_audit()
    {

    }

}
