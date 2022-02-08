<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class SchlesingerSurveysTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('schlesinger_surveys')->delete();

        DB::table('schlesinger_surveys')->insert(array(
            0 =>
                array(
                    'AccountId' => 1,
                    'BillingEntityId' => 2,
                    'CollectPII' => 0,
                    'CPI' => '1.58',
                    'created_at' => '2022-01-27 09:18:26',
                    'Group_UpdateTimeStamp' => '2022-01-29 23:55:06',
                    'id' => 64,
                    'IndustryId' => 1,
                    'IR' => '1.00',
                    'IsManualInc' => 0,
                    'IsMobileAllowed' => 1,
                    'IsNonMobileAllowed' => 1,
                    'IsQuotaLevelCPI' => 1,
                    'IsSurveyGroupExist' => 0,
                    'IsTabletAllowed' => 1,
                    'LanguageId' => 3,
                    'LiveLink' => 'https://qa-surveys.sample-cube.com?VID=30&SID=ABB7F0A0-BCA4-49A5-A698-4D8F8F58B43E&LID=3&vsid=[#scid#]',
                    'LOI' => 1,
                    'Qual_UpdateTimeStamp' => '2022-01-10 08:43:57',
                    'Quota_UpdateTimeStamp' => '2022-01-12 03:58:35',
                    'StudyTypeId' => 1,
                    'SurveyId' => 1080782,
                    'updated_at' => '2022-02-05 13:20:13',
                    'UpdateTimeStamp' => '2022-01-10 09:50:00',
                    'UrlTypeId' => 1,
                ),
        ));


    }
}
