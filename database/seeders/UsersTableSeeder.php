<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('users')->delete();

        DB::table('users')->insert(array(
            0 =>
                array(
                    'created_at' => '2021-06-21 15:47:31',
                    'email' => 'test@adbloom.co',
                    'email_verified_at' => NULL,
                    'id' => 1,
                    'name' => 'Deployer',
                    'password' => '$2y$10$LJvxxA7IlAQMrdeXNZfeI.ATUF4b6twyIxJ4Co2nEhOv7IjAkOJW6',
                    'remember_token' => 'dmbcIf9HyKrM4pPjHgUEK46bfuSV2ZbTQDEPjkT3hGv1vCsnVGE93RKpcfTF',
                    'updated_at' => '2021-08-04 15:56:00',
                ),
            1 =>
                array(
                    'created_at' => '2021-06-21 16:27:38',
                    'email' => 'alexander@adbloom.com',
                    'email_verified_at' => NULL,
                    'id' => 2,
                    'name' => 'Alexander Polyakov',
                    'password' => '$2y$10$aji..1BJTRIKAfvcUcK.9eP38dvdGMkn2FjG4kxb9EWug6ErT.TJy',
                    'remember_token' => 'KFBqdCcO0oYMQK5vDf7EPuSopTh5Zry3DLoCwF7XZg2kGYFBcNHyT1zIcNZc',
                    'updated_at' => '2021-06-21 16:27:38',
                ),
            2 =>
                array(
                    'created_at' => '2021-07-08 13:15:16',
                    'email' => 'andrew@adbloom.com',
                    'email_verified_at' => NULL,
                    'id' => 3,
                    'name' => 'Andrew Abony',
                    'password' => '$2y$10$k.8Kb8WvIHzTLYl3SkKXxeW0rSQBw2hchM8VA5NwlWZwYMBPSip0.',
                    'remember_token' => NULL,
                    'updated_at' => '2021-07-08 13:15:16',
                ),
        ));


    }
}
