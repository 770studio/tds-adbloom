<?php

namespace Tests\Unit;

use App\Services\StatsAlerts\StatsGroupBy;
use Tests\TestCase;

class StatsGroupByTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_returns_the_correct_output()
    {

         $this->assertSame(
            array_values(
                (new StatsGroupBy())
                    ->noPartner()
                    ->noHour()
                    ->toArray()
            )
                 ,
             [
                "Stat_offer_id",
                "Stat_offer_url_id",
                "Stat_goal_id",
                "Stat_date"
             ]
         );

    }

    public function test_it_can_create_from_array()
    {
        $this->assertSame(
            array_values( (new StatsGroupBy())
                ->createFromArray(['partners' => false, 'hour' => true])
                ->toArray()
            ),
            [
                  'Stat_offer_id',
                  'Stat_offer_url_id',
                  'Stat_goal_id',
                  'Stat_date',
                  'Stat_hour',
            ]
        );
    }

}
