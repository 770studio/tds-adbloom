<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class WidgetsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('widgets')->delete();

        DB::table('widgets')->insert(array(
            0 =>
                array(
                    'countries' => '[]',
                    'created_at' => '2021-08-19 00:05:32',
                    'dynamic_or_static' => 0,
                    'id' => 1,
                    'partner_id' => 1,
                    'platforms' => '["1", "2", "3"]',
                    'short_id' => 'FF-cYBEVYSqXJ2HyPUjsd',
                    'tags' => '[]',
                    'updated_at' => '2021-08-19 00:05:32',
                ),
            1 =>
                array(
                    'countries' => '[]',
                    'created_at' => '2021-08-19 00:19:37',
                    'dynamic_or_static' => 1,
                    'id' => 2,
                    'partner_id' => 1,
                    'platforms' => '[]',
                    'short_id' => 'Vhdpv2Jq6hk80A_kjBc2K',
                    'tags' => '[]',
                    'updated_at' => '2021-08-19 00:19:52',
                ),
            2 =>
                array(
                    'countries' => '["US"]',
                    'created_at' => '2021-08-19 09:02:41',
                    'dynamic_or_static' => 0,
                    'id' => 3,
                    'partner_id' => 2,
                    'platforms' => '[]',
                    'short_id' => 'nGE1H6GuGNnW06tAZoxs5',
                    'tags' => '["3"]',
                    'updated_at' => '2021-08-19 09:07:37',
                ),
            3 =>
                array(
                    'countries' => '[]',
                    'created_at' => '2021-08-19 09:08:08',
                    'dynamic_or_static' => 1,
                    'id' => 4,
                    'partner_id' => 2,
                    'platforms' => '[]',
                    'short_id' => 'SVLSaRWEFhiTd6TVT4FjT',
                    'tags' => '[]',
                    'updated_at' => '2021-09-13 09:39:50',
                ),
        ));


    }
}
