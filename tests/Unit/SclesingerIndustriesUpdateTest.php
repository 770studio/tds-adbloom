<?php

namespace Tests\Unit;

use App\Models\SchlesingerIndustry;
use App\Services\SchlesingerAPI\SchlesingerAPIService;
use App\Services\SchlesingerAPI\SchlesingerIndustryListResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Mockery\MockInterface;
use Tests\TestCase;

class SclesingerIndustriesUpdateTest extends TestCase
{
    public function load_json(): array
    {
        return json_decode(
            file_get_contents("tests/Schlesinger/industry-list.json"), true
        );

    }

    public function load_data_into_db()
    {


        $json = $this->load_json();

        collect(data_get($json, "industries"))
            ->chunk(500)
            ->each(function (Collection $industryChunk) {
                DB::transaction(function () use ($industryChunk) {
                    DB::table((new SchlesingerIndustry)->getTable())->delete();
                    SchlesingerIndustry::insert(
                        $industryChunk->toArray()
                    );


                });
            });
    }

    public function test_json_is_valid()
    {

        $json = $this->load_json();
        $this->assertEquals(true, data_get($json, "result.success"));
        $this->assertLessThan(5000, data_get($json, "result.totalCount"));
    }


    public function test_can_parse_data()
    {
        $json = $this->load_json();

        $this->assertCount(data_get($json, "result.totalCount")
            , data_get($json, "industries")
        );

    }


    public function test_can_add_to_db()
    {
        $this->load_data_into_db();
        $this->assertDatabaseCount((new SchlesingerIndustry)->getTable(), 32);

        $this->assertEmpty(
            array_diff([
                "IndustryId" => "1",
                "Description" => "Automotive"
            ], SchlesingerIndustry::first()->toArray())
        );


    }

    #TODO move to TestSchlesingerAPIService
    public function test_get_from_api()
    {

        $mock = $this->mock(SchlesingerAPIService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getIndustries')
                ->once()
                ->andReturn(
                    new SchlesingerIndustryListResponse(
                        $this->load_json()
                    )
                );
        });
        // mock (new SchlesingerAPIService)->getIndustries()
        $this->artisan("schlesinger-survey-industries:update");


        //TODO .....

    }
}

