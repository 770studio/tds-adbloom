<?php

namespace Tests\Feature;

use App\Services\StatsAlerts\FlexPeriod;
use App\Services\StatsAlerts\StatsAlertsInventoryService;
use App\Services\StatsAlerts\StatsAlertsService;
use App\Services\StatsAlerts\StatsGroupBy;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Carbon;
use Tests\TestCase_MySql;

class StatsAlertsTest extends TestCase_MySql
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

        $this->assertDatabaseCount('conversions_hourly_stats', 193211);

        // TuneAPIGetConversionPageJob::dispatch(1, []);
        //Artisan::call('conversions:update');
        // Artisan::call('conversions:collectHourlyStats --stat_date=2021-03-24 --stat_hour=11');
        //  $this->assertDatabaseCount('conversions_hourly_stats', 94);


    }

    public function test_offer_activity()
    {

        $recent = new  FlexPeriod(0);
        $recent->setCustomDates(Carbon::parse('01.12.2021')->startOfDay(),
            Carbon::parse('15.12.2021')->endOfDay()
        );
        $older = new  FlexPeriod(0);
        $older->setCustomDates(Carbon::parse('16.12.2021')->startOfDay(),
            Carbon::parse('16.12.2021')->endOfDay()
        );

        $s = new StatsAlertsService(
            new StatsAlertsInventoryService(
                new StatsGroupBy()
            )
        );

        $s->testAlert3($recent, $older);
        $this->assertEquals(17, $s->getAlerts()->count());
    }

    // alert2
    public function test_Offers_CR()
    {

        $recent = new  FlexPeriod(0);
        $recent->setCustomDates(
            Carbon::parse("2022-01-26 00:00:00"),
            Carbon::parse("2022-01-26 23:59:59")
        );
        $older = new  FlexPeriod(0);
        $older->setCustomDates(Carbon::parse("2021-12-26 00:00:00"),
            Carbon::parse("2022-01-25 23:59:59")
        );

        $s = new StatsAlertsService(
            new StatsAlertsInventoryService(
                new StatsGroupBy()
            )
        );
        $s->testAlert2($recent, $older);
        $this->assertEquals(1, $s->getAlerts()->count());
    }

    /**
     * Конверсии по оферу по целе по всем партнёрам упали до 0. Это значит отвалилась интеграция. Проверить сначала прошлый час, если в прошлый час тоже было 0, нет alert, проверить прошлые 24 час, если нули, то нет alert. Если прошлый час больше 0 и болье 5 (threshold), alert.
     */
    /*    public function test_example_inventory_audit()
        {


        }*/

}
