<?php

namespace Tests;

use Exception;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase_MySql extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('config:clear'); // чтоб перейти к тест окружению!!!
        if (app()->environment() != 'testing') throw new Exception('wrong env');

    }


}


