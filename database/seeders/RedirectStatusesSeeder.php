<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RedirectStatusesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        DB::table('redirect_statuses')->truncate();
        DB::table('redirect_statuses')->insert([
            ['code' => 'success'],
            ['code' => 'reject'],
            ['code' => 'oq'],
            ['code' => 'dq']
        ]);


    }
}
