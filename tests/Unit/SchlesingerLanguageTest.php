<?php

namespace Tests\Feature\Models;

use App\Models\Integrations\Schlesinger\SchlesingerLanguage;
use App\Services\SchlesingerAPI\SchlesingerAPIService;
use App\Services\SchlesingerAPI\SchlesingerLanguageListResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Mockery\MockInterface;
use Tests\TestCase;

class SchlesingerLanguageTest extends TestCase
{
    public function test_json_is_valid()
    {

        $json = $this->load_json();

        $this->assertEquals(true, data_get($json, "result.success"));
        $this->assertEquals(122, data_get($json, "result.totalCount"));
    }

    public function load_json(): array
    {
        return json_decode(
            file_get_contents("tests/Schlesinger/language-list.json"), true
        );

    }

    public function test_can_parse_data()
    {
        $json = $this->load_json();

        $this->assertCount(data_get($json, "result.totalCount")
            , data_get($json, "languages")
        );

    }

    public function test_can_add_to_db()
    {
        $this->load_data_into_db();

        $this->assertDatabaseCount((new SchlesingerLanguage())->getTable(), 122);

        $this->assertEmpty(
            array_diff([
                "LanguageId" => "1",
                "Description" => "English - United Kingdom"
            ], SchlesingerLanguage::first()->toArray())
        );


    }

    public function load_data_into_db()
    {


        $json = $this->load_json();

        collect(data_get($json, "languages"))
            ->chunk(500)
            ->each(function (Collection $langChunk) {
                DB::transaction(function () use ($langChunk) {
                    DB::table((new SchlesingerLanguage())->getTable())->delete();
                    SchlesingerLanguage::insert(
                        $langChunk->toArray()
                    );


                });
            });
    }

    
    public function test_get_from_api()
    {

        $mock = $this->mock(SchlesingerAPIService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getLanguages')
                ->once()
                ->andReturn(
                    new SchlesingerLanguageListResponse(
                        $this->load_json()
                    )
                );
        });
        // mock (new SchlesingerAPIService)->getIndustries()
        $this->artisan("schlesinger-languages:update");

        $this->assertDatabaseCount((new SchlesingerLanguage())->getTable(), 122);


    }
}
