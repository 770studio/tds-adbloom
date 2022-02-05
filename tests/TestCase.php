<?php

namespace Tests;

use App\Services\SchlesingerAPI\SchlesingerAPIService;
use App\Services\SchlesingerAPI\SchlesingerIndustryListResponse;
use App\Services\SchlesingerAPI\SchlesingerQualificationsListResponse;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('config:clear'); // чтоб перейти к тест окружению!!!
        if (app()->environment() != 'testing') throw new Exception('wrong env');
        //$this->artisan('migrate:fresh --seed --path="./tests/database" ');
        // $app->useDatabasePath(base_path('tests/database')); //example path

        Artisan::call(
            'db:seed', ['--class' => 'DatabaseSeeder']
        );
        // $this->artisan("db:seed");


    }


}


class FakeSchlesingerAPIService extends SchlesingerAPIService
{
    public function getIndustries()
    {
        return new SchlesingerIndustryListResponse(
            json_decode(
                file_get_contents("tests/Schlesinger/industry-list.json"), true
            )
        );


    }

    public function getQualificationsByLangID(int $languageId): SchlesingerQualificationsListResponse
    {
        return new SchlesingerQualificationsListResponse(json_decode(
            file_get_contents("tests/Schlesinger/qualifications.json")
        ));

    }
}
