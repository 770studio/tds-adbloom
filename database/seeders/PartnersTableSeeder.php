<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class PartnersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('partners')->delete();

        DB::table('partners')->insert(array(
            0 =>
                array(
                    'convert_to_points' => 0,
                    'created_at' => '2021-07-13 14:38:08',
                    'external_id' => '1246',
                    'id' => 1,
                    'name' => 'Partner A',
                    'pending_timeout' => 5,
                    'pending_url' => 'retrjt',
                    'percentage' => 33,
                    'points_logo' => NULL,
                    'points_multiplier' => '1',
                    'points_name' => NULL,
                    'rev_share' => 1,
                    'send_pending_postback' => 0,
                    'send_pending_status' => '{"success":true,"oq":false,"dq":false,"reject":false}',
                    'short_id' => 'kZHgYLW9-D20NlCSzNpnl',
                    'updated_at' => '2021-08-16 13:08:24',
                ),
            1 =>
                array(
                    'convert_to_points' => 1,
                    'created_at' => '2021-07-13 22:26:44',
                    'external_id' => '2138',
                    'id' => 2,
                    'name' => 'Shop Your Way',
                    'pending_timeout' => 1,
                    'pending_url' => 'https://rewards.uat.telluride.transformco.com/tellurideAS/events/AdbloomWebHook/?access_token=7ab20d21771b1e81bc5bae84cc89b913&currency={currency}&datetime={datetime}&datetimeUpdated={datetimeUpdated}&eid={eventId}&payout={payout}&point={userPoints}&status={status}&surveyId={externalId}&surveyName={name}&uid={parnterSub5}&userPayout={userPayout}',
                    'percentage' => 20,
                    'points_logo' => NULL,
                    'points_multiplier' => '53',
                    'points_name' => 'Shopperbucks',
                    'rev_share' => 1,
                    'send_pending_postback' => 1,
                    'send_pending_status' => '{"success":true,"oq":true,"dq":true,"reject":true}',
                    'short_id' => 'QhZ1CWIkqWXaIj-TUga1W',
                    'updated_at' => '2021-09-19 12:40:25',
                ),
        ));


    }
}
