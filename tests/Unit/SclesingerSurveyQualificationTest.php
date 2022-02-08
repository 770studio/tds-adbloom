<?php

namespace Tests\Unit;

use App\Models\Integrations\Schlesinger\SchlesingerSurveyQualification;
use App\Models\Integrations\Schlesinger\SchlesingerSurveyQualificationQuestion;
use App\Models\SchlesingerSurvey;
use App\Services\SchlesingerAPI\SchlesingerAPIService;
use App\Services\SchlesingerAPI\SchlesingerSurveyQualificationsListResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Mockery\MockInterface;
use Tests\TestCase;

class SclesingerSurveyQualificationTest extends TestCase
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

    public function test_can_get_the_seeded()
    {
        $Surveys = SchlesingerSurvey::all();
        $this->assertEquals(1, $Surveys->count());
    }

    public function test_can_proccess_queue_job()
    {


        $json = $this->load_json();
        $SurveyId = 1080782;
        $this->assertSame($SurveyId, data_get($json, "SurveyId"));
        // the flow uses QueueJob, each surveyid in a separate call
        $survey_internalId = SchlesingerSurvey::whereSurveyid($SurveyId)
            ->first()
            ->getKey();

        $this->assertEquals(64, $survey_internalId);

        SchlesingerSurveyQualification::whereSurveyInternalid($survey_internalId)
            ->delete();

        collect(data_get($json, "SurveyQualifications"))
            ->each(function (array $item) use ($survey_internalId) {
                DB::transaction(function () use ($item, $survey_internalId) {
                    $QualificationId = Arr::pull($item, 'QualificationId');


                    if (!$qualification = SchlesingerSurveyQualificationQuestion::whereQualificationid($QualificationId)
                        ->first()
                    ) {

                        return;
                    }


                    SchlesingerSurveyQualification::create(
                        array_merge($item, [
                            'survey_internalId' => $survey_internalId
                            , 'qualification_internalId' => $qualification->getKey()
                        ])
                    );


                });
            });

        $this->assertDatabaseCount((new SchlesingerSurveyQualification)->getTable(), 3);


    }


    #TODO move to TestSchlesingerAPIService

    public function test_get_from_api()
    {

        $mock = $this->mock(SchlesingerAPIService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getQualificationAdmitCriteria')
                ->once()
                ->andReturn(
                    new SchlesingerSurveyQualificationsListResponse(
                        $this->load_json()
                    )
                );
        });

        $this->artisan("schlesinger-survey-qualifications:update");

        $this->assertDatabaseCount((new SchlesingerSurveyQualification())->getTable(), 3);


    }
}
