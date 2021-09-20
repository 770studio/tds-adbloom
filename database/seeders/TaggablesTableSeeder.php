<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class TaggablesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('taggables')->delete();

        DB::table('taggables')->insert(array(
            0 =>
                array(
                    'created_at' => NULL,
                    'tag_id' => 2,
                    'taggable_id' => 2,
                    'taggable_type' => 'App\\Models\\Opportunity',
                    'updated_at' => NULL,
                ),
            1 =>
                array(
                    'created_at' => NULL,
                    'tag_id' => 3,
                    'taggable_id' => 7,
                    'taggable_type' => 'App\\Models\\Opportunity',
                    'updated_at' => NULL,
                ),
            2 =>
                array(
                    'created_at' => NULL,
                    'tag_id' => 3,
                    'taggable_id' => 1,
                    'taggable_type' => 'App\\Models\\Opportunity',
                    'updated_at' => NULL,
                ),
        ));


    }
}
