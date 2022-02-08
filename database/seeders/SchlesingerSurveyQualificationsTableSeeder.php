<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class SchlesingerSurveyQualificationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('schlesinger_survey_qualifications')->delete();

        DB::table('schlesinger_survey_qualifications')->insert(array(
            0 =>
                array(
                    'AnswerCodes' => '["1"]',
                    'AnswerIds' => '["18-60"]',
                    'created_at' => '2022-02-08 17:24:35',
                    'id' => 1,
                    'qualification_internalId' => 10465,
                    'survey_internalId' => 64,
                    'updated_at' => '2022-02-08 17:24:35',
                    'UpdateTimeStamp' => '2022-01-10 08:43:58',
                ),
            1 =>
                array(
                    'AnswerCodes' => '["1", "2", "3", "4"]',
                    'AnswerIds' => '["58", "59", "193971", "193974"]',
                    'created_at' => '2022-02-08 17:24:35',
                    'id' => 2,
                    'qualification_internalId' => 10466,
                    'survey_internalId' => 64,
                    'updated_at' => '2022-02-08 17:24:35',
                    'UpdateTimeStamp' => '2022-01-10 08:43:58',
                ),
            2 =>
                array(
                    'AnswerCodes' => '["1"]',
                    'AnswerIds' => '["10005,90001"]',
                    'created_at' => '2022-02-08 17:24:35',
                    'id' => 3,
                    'qualification_internalId' => 9796,
                    'survey_internalId' => 64,
                    'updated_at' => '2022-02-08 17:24:35',
                    'UpdateTimeStamp' => '2022-01-10 08:43:58',
                ),
        ));


    }
}
