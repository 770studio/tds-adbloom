<?php

namespace Tests\Feature;

use App\Services\StatsAlerts\FlexPeriod;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class StatsAlertsTest extends TestCase
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

    public function test_alert2()
    {

        Artisan::call('conversions:collectHourlyStats --stat_date=2021-03-24 --stat_hour=11');
        $period = new FlexPeriod(0)->setCustomDates()

    }
    /**
     * Конверсии по оферу по целе по всем партнёрам упали до 0. Это значит отвалилась интеграция. Проверить сначала прошлый час, если в прошлый час тоже было 0, нет alert, проверить прошлые 24 час, если нули, то нет alert. Если прошлый час больше 0 и болье 5 (threshold), alert.
     */
    /*    public function test_example_inventory_audit()
        {


        }*/

}
