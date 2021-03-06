<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class OpportunitiesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('opportunities')->delete();

        DB::table('opportunities')->insert(array(
            0 =>
                array(
                    'age_from' => NULL,
                    'age_to' => NULL,
                    'call_to_action' => NULL,
                    'client_id' => 1,
                    'countries' => '["AL", "DZ", "AS"]',
                    'created_at' => '2021-07-13 14:53:11',
                    'currency' => 'USD',
                    'description' => 'An experiment survey to test the integration.',
                    'external_id' => '372',
                    'genders' => NULL,
                    'id' => 1,
                    'image' => NULL,
                    'incentive' => NULL,
                    'link' => 'https://trk.adbloom.co/aff_c?offer_id=372&aff_id={partnerId}&aff_click_id={clickId}&aff_sub5={userId}&aff_unique2={birthdate}&aff_unique3={email}&aff_sub2={country}&aff_sub4={gender}&source=widget',
                    'name' => 'Survey Integration Test',
                    'payout' => '1.00000',
                    'platforms' => '["2"]',
                    'short_id' => 'auYzkuvln12C2yQyiw2zT',
                    'targeting_params' => NULL,
                    'timeToComplete' => 2,
                    'type' => 'survey',
                    'updated_at' => '2021-09-15 18:36:45',
                ),
            1 =>
                array(
                    'age_from' => NULL,
                    'age_to' => NULL,
                    'call_to_action' => NULL,
                    'client_id' => 2,
                    'countries' => NULL,
                    'created_at' => '2021-08-12 09:03:02',
                    'currency' => 'USD',
                    'description' => NULL,
                    'external_id' => '195',
                    'genders' => NULL,
                    'id' => 4,
                    'image' => 'c6b658bbe40fba4f354a93f3709c5458135a68dc.png',
                    'incentive' => NULL,
                    'link' => NULL,
                    'name' => 'Root Insurance - US',
                    'payout' => '0.00000',
                    'platforms' => NULL,
                    'short_id' => 'u1x4jy0wPmU8tQWUl-xXu',
                    'targeting_params' => NULL,
                    'timeToComplete' => 0,
                    'type' => 'offer',
                    'updated_at' => '2021-08-12 09:18:48',
                ),
            2 =>
                array(
                    'age_from' => NULL,
                    'age_to' => NULL,
                    'call_to_action' => NULL,
                    'client_id' => 1,
                    'countries' => NULL,
                    'created_at' => '2021-08-12 09:21:06',
                    'currency' => 'USD',
                    'description' => NULL,
                    'external_id' => '268',
                    'genders' => NULL,
                    'id' => 5,
                    'image' => 'a8760354e61bbef5750ac1f47f208fc6d00b1adc.png',
                    'incentive' => NULL,
                    'link' => NULL,
                    'name' => 'Fetch Rewards - US [iOS] *Primary',
                    'payout' => '2.50000',
                    'platforms' => NULL,
                    'short_id' => 'GrQTdz7VjJrNApBh2Lv_A',
                    'targeting_params' => NULL,
                    'timeToComplete' => 0,
                    'type' => 'offer',
                    'updated_at' => '2021-08-12 09:21:06',
                ),
            3 =>
                array(
                    'age_from' => NULL,
                    'age_to' => NULL,
                    'call_to_action' => NULL,
                    'client_id' => 1,
                    'countries' => '[]',
                    'created_at' => '2021-08-12 09:23:37',
                    'currency' => 'USD',
                    'description' => NULL,
                    'external_id' => '282',
                    'genders' => NULL,
                    'id' => 6,
                    'image' => '29221df8ee486b07b3d9e74089314fa6f19a0a9b.png',
                    'incentive' => NULL,
                    'link' => NULL,
                    'name' => 'Acorns',
                    'payout' => '17.00000',
                    'platforms' => '["2", "3"]',
                    'short_id' => 'STePBK2WuOGOcfbyab-sR',
                    'targeting_params' => NULL,
                    'timeToComplete' => 0,
                    'type' => 'offer',
                    'updated_at' => '2021-09-25 08:11:59',
                ),
            4 =>
                array(
                    'age_from' => NULL,
                    'age_to' => NULL,
                    'call_to_action' => NULL,
                    'client_id' => 1,
                    'countries' => NULL,
                    'created_at' => '2021-08-12 09:25:12',
                    'currency' => 'USD',
                    'description' => NULL,
                    'external_id' => '320',
                    'genders' => NULL,
                    'id' => 7,
                    'image' => '7341deebff0f92e04891e4744191c03a60e5a523.png',
                    'incentive' => NULL,
                    'link' => NULL,
                    'name' => 'Pinecone Research - US',
                    'payout' => '0.00000',
                    'platforms' => NULL,
                    'short_id' => 'MIZzRZtPRlxu1SiSnghAn',
                    'targeting_params' => NULL,
                    'timeToComplete' => 0,
                    'type' => 'offer',
                    'updated_at' => '2021-08-12 10:09:07',
                ),
            5 =>
                array(
                    'age_from' => 8,
                    'age_to' => 89,
                    'call_to_action' => NULL,
                    'client_id' => 1,
                    'countries' => '["AD", "AX", "AO", "AR", "AZ"]',
                    'created_at' => '2021-08-12 09:26:53',
                    'currency' => 'USD',
                    'description' => NULL,
                    'external_id' => '145',
                    'genders' => '["2", "1"]',
                    'id' => 8,
                    'image' => NULL,
                    'incentive' => NULL,
                    'link' => NULL,
                    'name' => 'Springboard America',
                    'payout' => '78.55000',
                    'platforms' => '["2"]',
                    'short_id' => 'Aan7LGGfWhZz0IqeUZfCE',
                    'targeting_params' => NULL,
                    'timeToComplete' => 0,
                    'type' => 'offer',
                    'updated_at' => '2021-09-25 09:26:29',
                ),
            6 =>
                array(
                    'age_from' => NULL,
                    'age_to' => NULL,
                    'call_to_action' => NULL,
                    'client_id' => 1,
                    'countries' => NULL,
                    'created_at' => '2021-08-12 09:27:50',
                    'currency' => 'USD',
                    'description' => NULL,
                    'external_id' => '228',
                    'genders' => NULL,
                    'id' => 9,
                    'image' => '8f9bcc5473e572ef21dab26ee700f4986813be17.png',
                    'incentive' => NULL,
                    'link' => NULL,
                    'name' => 'Toluna Influencers',
                    'payout' => '0.00000',
                    'platforms' => NULL,
                    'short_id' => 'h7G0RnTjSL0IQw4vYGlY4',
                    'targeting_params' => NULL,
                    'timeToComplete' => 0,
                    'type' => 'offer',
                    'updated_at' => '2021-08-12 09:27:50',
                ),
            7 =>
                array(
                    'age_from' => NULL,
                    'age_to' => NULL,
                    'call_to_action' => 'Try Now',
                    'client_id' => 1,
                    'countries' => '["AL", "AD", "AF"]',
                    'created_at' => '2021-08-12 10:09:39',
                    'currency' => 'USD',
                    'description' => NULL,
                    'external_id' => '322',
                    'genders' => '["2"]',
                    'id' => 10,
                    'image' => 'dd3b701c55a259b7a4eabf4affedb5ea17302442.png',
                    'incentive' => 'Get $5 when signup',
                    'link' => 'https://site.com',
                    'name' => 'Pinecone Research - CA [EN]',
                    'payout' => '5.00000',
                    'platforms' => '["2", "3", "1"]',
                    'short_id' => 'QPap7xfYc-SQNRfgCAo0m',
                    'targeting_params' => '["2", "3"]',
                    'timeToComplete' => 0,
                    'type' => 'offer',
                    'updated_at' => '2021-09-17 14:07:52',
                ),
        ));


    }
}
