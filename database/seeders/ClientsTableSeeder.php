<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('clients')->delete();

        DB::table('clients')->insert(array(
            0 =>
                array(
                    'created_at' => '2021-07-13 14:48:54',
                    'email' => 'alex+1@adbloom.com',
                    'external_id' => '648',
                    'id' => 1,
                    'name' => 'Adbloom Inc.',
                    'redirect_to_domain' => 'https://widget.adbloom.co',
                    'short_id' => 'o8BVO8WCBjfywg3RYAqVH',
                    'status' => 'active',
                    'updated_at' => '2021-07-14 09:18:41',
                ),
            1 =>
                array(
                    'created_at' => '2021-08-12 09:03:41',
                    'email' => 'root@adbloom.com',
                    'external_id' => '605',
                    'id' => 2,
                    'name' => 'Root Insurance',
                    'redirect_to_domain' => NULL,
                    'short_id' => 'Z0dHZQiwO_HjAFnwBft9k',
                    'status' => 'active',
                    'updated_at' => '2021-08-12 09:03:41',
                ),
            2 =>
                array(
                    'created_at' => '2021-08-17 15:22:23',
                    'email' => 'dalia@adbloom.com',
                    'external_id' => '1123',
                    'id' => 3,
                    'name' => 'Dalia',
                    'redirect_to_domain' => NULL,
                    'short_id' => 'aYE7icUjlriYtNMHrSX1r',
                    'status' => 'active',
                    'updated_at' => '2021-08-17 15:22:23',
                ),
            3 =>
                array(
                    'created_at' => '2021-08-17 15:36:50',
                    'email' => NULL,
                    'external_id' => 'Aute nostrud reprehe',
                    'id' => 4,
                    'name' => 'Inez Munoz',
                    'redirect_to_domain' => NULL,
                    'short_id' => '_Bcts2qeiUzh01845ougZ',
                    'status' => 'paused',
                    'updated_at' => '2021-08-17 15:36:50',
                ),
        ));


    }
}
