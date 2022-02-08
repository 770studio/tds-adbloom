<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class SchlesingerSurveyQualificationAnswersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('schlesinger_survey_qualification_answers')->delete();

        DB::table('schlesinger_survey_qualification_answers')->insert(array(
            0 =>
                array(
                    'answerCode' => '1',
                    'answerId' => 12476,
                    'created_at' => '2022-02-08 12:17:57',
                    'id' => 120760,
                    'qualification_internalId' => 9796,
                    'text' => 'Zip',
                    'updated_at' => '2022-02-08 12:17:57',
                ),
            1 =>
                array(
                    'answerCode' => '1',
                    'answerId' => 36027,
                    'created_at' => '2022-02-08 12:18:17',
                    'id' => 128486,
                    'qualification_internalId' => 10465,
                    'text' => 'Age',
                    'updated_at' => '2022-02-08 12:18:17',
                ),
            2 =>
                array(
                    'answerCode' => '1',
                    'answerId' => 19821,
                    'created_at' => '2022-02-08 12:18:17',
                    'id' => 128487,
                    'qualification_internalId' => 10466,
                    'text' => 'Male',
                    'updated_at' => '2022-02-08 12:18:17',
                ),
            3 =>
                array(
                    'answerCode' => '2',
                    'answerId' => 19822,
                    'created_at' => '2022-02-08 12:18:17',
                    'id' => 128488,
                    'qualification_internalId' => 10466,
                    'text' => 'Female',
                    'updated_at' => '2022-02-08 12:18:17',
                ),
        ));


    }
}
