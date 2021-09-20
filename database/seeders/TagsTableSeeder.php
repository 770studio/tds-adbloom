<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('tags')->delete();

        DB::table('tags')->insert(array(
            0 =>
                array(
                    'created_at' => '2021-07-21 22:27:59',
                    'id' => 1,
                    'name' => 'Test',
                    'updated_at' => '2021-08-04 15:55:37',
                ),
            1 =>
                array(
                    'created_at' => '2021-07-22 13:42:04',
                    'id' => 2,
                    'name' => 'Covid 19',
                    'updated_at' => '2021-07-22 13:42:04',
                ),
            2 =>
                array(
                    'created_at' => '2021-08-19 00:18:33',
                    'id' => 3,
                    'name' => 'McKenzie Griffin',
                    'updated_at' => '2021-08-19 00:18:33',
                ),
        ));


    }
}
