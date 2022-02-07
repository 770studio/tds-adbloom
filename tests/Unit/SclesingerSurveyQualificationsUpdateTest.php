<?php

namespace Tests\Unit;

use App\Models\SchlesingerSurveyQualification;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class SclesingerSurveyQualificationsUpdateTest extends TestCase
{

    /**
     *  TODO mb share between others
     */
    public function test_json_is_valid()
    {
        $json = $this->load_json();
        $this->assertEquals(true, data_get($json, "Result.Success"));
        $this->assertLessThan(5000, data_get($json, "Result.TotalCount"));
    }

    public function load_json()
    {

        return json_decode(
            file_get_contents("tests/Schlesinger/admit-criteria.json"), true
        );


    }

    public function test_can_parse_data()
    {
        $json = $this->load_json();

        $this->assertCount(data_get($json, "Result.TotalCount")
            , data_get($json, "SurveyQualifications")
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

    public function load_data_into_db()
    {


        $json = $this->load_json();

        collect(data_get($json, "SurveyQualifications"))
            ->chunk(500)
            ->each(function (Collection $chunk) {
                dd($chunk);
                DB::transaction(function () use ($industryChunk) {
                    DB::table((new SchlesingerSurveyQualification())->getTable())->delete();
                    SchlesingerIndustry::insert(
                        $industryChunk->toArray()
                    );


                });
            });
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
        $this->artisan("schlesinger-industries:update");

        $this->assertDatabaseCount((new SchlesingerIndustry)->getTable(), 32);


    }
}
