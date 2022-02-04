<?php

namespace Tests\Unit;

use App\Models\SchlesingerSurveyQualificationAnswer;
use App\Models\SchlesingerSurveyQualificationQuestion;
use Illuminate\Support\Collection;
use Tests\TestCase;

class TestSchlesingerQualificationsUpdate extends TestCase
{


    public function test_json_is_valid()
    {

        $json = json_decode(
            file_get_contents("tests/Schlesinger/qualifications.json")
        );

        $this->assertEquals(true, $json->result->success);
        $this->assertLessThan(5000, $json->result->totalCount);
        return $json;
    }

    /**
     * @depends test_json_is_valid
     */
    public function test_can_parse_data($json)
    {
        $this->assertCount($json->result->totalCount,
            $json->qualifications
        );

        return $json->qualifications;
    }

    /**
     * @depends test_can_parse_data
     */
    public function can_transform_to_qualification_db_format($qualifications)
    {
        $LanguageId = 3; // eng ?
        $c = collect($qualifications);
        $clone = clone($c);
        $this->assertEquals([
            "qualificationId" => 115,
            "name" => "STANDARD_PUBLICATIONS",
            "text" => "Which types of publications do you read?",
            "qualificationCategoryId" => 1,
            "qualificationTypeId" => 2,
            "LanguageId" => 3,
        ], (array)$clone
            ->map(function ($item) use ($LanguageId) {
                $item->LanguageId = $LanguageId;
                unset($item->qualificationAnswers);
                return $item;
            })->first());
    }

    /**
     * @depends test_can_parse_data
     */
    public function test_can_add_questions_to_db($qualifications)
    {
        collect($qualifications)
            ->take(10)
            ->each(function ($qualification) {
                $answers = $qualification->qualificationAnswers;
                unset($qualification->qualificationAnswers);
                /** @var SchlesingerSurveyQualificationQuestion $qualificationModel */
                $qualificationModel = SchlesingerSurveyQualificationQuestion::updateOrCreate(
                    ['LanguageId' => 3, 'qualificationId' => $qualification->qualificationId],
                    (array)$qualification
                );

                collect($answers)
                    ->transform(function (object $item) use ($qualificationModel) {
                        $item->qualification_internalId = $qualificationModel->id;
                        return (array)$item;
                    })
                    ->chunk(500)
                    ->each(function ($answersChunk) {
                        SchlesingerSurveyQualificationAnswer::upsert(
                            $answersChunk->toArray(),
                            ["qualification_internalId", "answerId"],
                            array_keys($answersChunk->first())
                        );
                    });

            });

        $this->assertDatabaseCount((new SchlesingerSurveyQualificationQuestion)->getTable()
            , 10);
        $this->assertDatabaseCount((new SchlesingerSurveyQualificationAnswer)->getTable()
            , 241);


    }

    public function dfdfnndf()
    {
        parseData()
            ->transform(function ($item) {
                dd($item);
                $item->LanguageId = $this->LanguageId;
                return $item;
            })
            ->chunk(500)
            ->each(function (Collection $qualificationChunk) {

                SchlesingerSurveyQualificationQuestion::upsert(
                    $qualificationChunk->toArray(),
                    ['LanguageId', 'qualificationId'],
                    ['name', 'text', 'qualificationCategoryId', 'qualificationTypeId', 'qualificationCategoryGDPRId']
                );


            })
            ->transform(function (object $item) {
                // remove anything except qualificationAnswers
                $newItem = $item->qualificationAnswers;
                // add qualificationId
                $newItem->qualificationId = $item->qualificationId;
                return $newItem;
            })
            ->each(function (Collection $answersChunk) {
                SchlesingerSurveyQualificationAnswer::upsert(
                    $answersChunk->toArray(),
                    ["qualification_internalId", "answerId"],
                    array_keys((array)$answersChunk->first())
                );

            });
    }


}
