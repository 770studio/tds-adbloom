<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class WidgetOpportunityTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('widget_opportunity')->delete();

        DB::table('widget_opportunity')->insert(array(
            0 =>
                array(
                    'opportunity_id' => 1,
                    'widget_id' => 1,
                ),
            1 =>
                array(
                    'opportunity_id' => 4,
                    'widget_id' => 1,
                ),
            2 =>
                array(
                    'opportunity_id' => 4,
                    'widget_id' => 2,
                ),
            3 =>
                array(
                    'opportunity_id' => 6,
                    'widget_id' => 2,
                ),
            4 =>
                array(
                    'opportunity_id' => 7,
                    'widget_id' => 2,
                ),
            5 =>
                array(
                    'opportunity_id' => 1,
                    'widget_id' => 2,
                ),
            6 =>
                array(
                    'opportunity_id' => 5,
                    'widget_id' => 2,
                ),
            7 =>
                array(
                    'opportunity_id' => 8,
                    'widget_id' => 2,
                ),
            8 =>
                array(
                    'opportunity_id' => 9,
                    'widget_id' => 2,
                ),
            9 =>
                array(
                    'opportunity_id' => 10,
                    'widget_id' => 2,
                ),
            10 =>
                array(
                    'opportunity_id' => 6,
                    'widget_id' => 3,
                ),
            11 =>
                array(
                    'opportunity_id' => 4,
                    'widget_id' => 3,
                ),
            12 =>
                array(
                    'opportunity_id' => 1,
                    'widget_id' => 3,
                ),
            13 =>
                array(
                    'opportunity_id' => 10,
                    'widget_id' => 3,
                ),
            14 =>
                array(
                    'opportunity_id' => 6,
                    'widget_id' => 4,
                ),
            15 =>
                array(
                    'opportunity_id' => 7,
                    'widget_id' => 4,
                ),
            16 =>
                array(
                    'opportunity_id' => 8,
                    'widget_id' => 4,
                ),
            17 =>
                array(
                    'opportunity_id' => 9,
                    'widget_id' => 4,
                ),
        ));


    }
}
