<?php

namespace Tests;

use Exception;

trait MigrateFreshSeedOnce
{
    /**
     * If true, setup has run at least once.
     * @var boolean
     */
    protected static $setUpHasRunOnce = false;

    /**
     * After the first run of setUp "migrate:fresh --seed"
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        if (!static::$setUpHasRunOnce) {
            $this->artisan('config:clear');// чтоб перейти к тест окружению!!!

            if ($this->app->environment() != 'testing') throw new Exception('wrong env');
            //$this->app->useDatabasePath(base_path('tests/database')); //example path
            $this->artisan('migrate:fresh --seed --path="./tests/database" ');

            //dd(DB::getDefaultConnection());
            //   dd(DB::getDefaultConnection(), DB::table('opportunities')->get());
            /*        Artisan::call(
                        'db:seed', ['--class' => 'DatabaseSeeder']
                    );*/
            static::$setUpHasRunOnce = true;
        }
    }

    protected function tearDown(): void
    {

    }
}
