<?php

namespace Tests;

use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('config:clear'); // чтоб перейти к тест окружению!!!
        if (app()->environment() !== 'testing') throw new Exception('wrong env');
        if (DB::getDefaultConnection() !== 'sqlite') throw new Exception('wrong db');


        //$this->artisan('migrate:fresh --seed --path="./tests/database" ');
        // $app->useDatabasePath(base_path('tests/database')); //example path

        Artisan::call(
            'db:seed', ['--class' => 'DatabaseSeeder']
        );
        // $this->artisan("db:seed");


    }


}


