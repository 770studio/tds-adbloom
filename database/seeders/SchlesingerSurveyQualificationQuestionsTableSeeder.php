<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class SchlesingerSurveyQualificationQuestionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('schlesinger_survey_qualification_questions')->delete();

        DB::table('schlesinger_survey_qualification_questions')->insert(array(
            0 =>
                array(
                    'created_at' => '2022-02-08 12:17:57',
                    'id' => 9796,
                    'LanguageId' => 13,
                    'name' => 'ZIP',
                    'qualificationCategoryGDPRId' => NULL,
                    'qualificationCategoryId' => 5,
                    'qualificationId' => 143,
                    'qualificationTypeId' => 3,
                    'text' => '¿Cuál es su Código Postal?',
                    'updated_at' => '2022-02-08 12:17:57',
                ),
            1 =>
                array(
                    'created_at' => '2022-02-08 12:18:17',
                    'id' => 10465,
                    'LanguageId' => 6,
                    'name' => 'Age',
                    'qualificationCategoryGDPRId' => NULL,
                    'qualificationCategoryId' => 5,
                    'qualificationId' => 59,
                    'qualificationTypeId' => 6,
                    'text' => 'What is your age?',
                    'updated_at' => '2022-02-08 12:18:17',
                ),
            2 =>
                array(
                    'created_at' => '2022-02-08 12:18:17',
                    'id' => 10466,
                    'LanguageId' => 6,
                    'name' => 'GENDER',
                    'qualificationCategoryGDPRId' => NULL,
                    'qualificationCategoryId' => 5,
                    'qualificationId' => 60,
                    'qualificationTypeId' => 1,
                    'text' => 'What is your gender?',
                    'updated_at' => '2022-02-08 12:18:17',
                ),
        ));


    }
}
