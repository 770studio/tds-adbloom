<?php

namespace Tests\Unit;

use App\Models\SchlesingerSurveyQualificationAnswer;
use App\Models\SchlesingerSurveyQualificationQuestion;
use Illuminate\Support\Facades\DB;
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

    /**
     *
     */
    public function test_can_add_to_db_another_way()
    {
        $this->questions_table = DB::table((new SchlesingerSurveyQualificationQuestion)->getTable());
        $this->answers_table = DB::table((new SchlesingerSurveyQualificationAnswer)->getTable());
        $json = json_decode(
            file_get_contents("tests/Schlesinger/qualifications.json")
        );
        collect($json->qualifications)
            ->take(10)
            ->each(function (object $qualification) {

                DB::transaction(function () use ($qualification) {

                    $answers = $qualification->qualificationAnswers;
                    unset($qualification->qualificationAnswers);
                    $this->questions_table->where('qualificationId', $qualification->qualificationId)
                        ->delete();
                    $qualificationModel = SchlesingerSurveyQualificationQuestion::create(
                        array_merge((array)$qualification, ['LanguageId' => 3])
                    );
                    $this->answers_table->where("qualification_internalId", $qualificationModel->id)->delete();

                    collect($answers)
                        ->transform(function (object $item) use ($qualificationModel) {
                            $item->qualification_internalId = $qualificationModel->id;
                            return (array)$item;
                        })
                        ->chunk(500)
                        ->each(function ($answersChunk) {
                            $this->answers_table->insert($answersChunk->toArray());

                        });
                });
            });

        $this->assertDatabaseCount((new SchlesingerSurveyQualificationQuestion)->getTable()
            , 10);
        $this->assertDatabaseCount((new SchlesingerSurveyQualificationAnswer)->getTable()
            , 241);

    }

    public function test_can_delete_related_answers()
    {
        $this->questions_table = DB::table((new SchlesingerSurveyQualificationQuestion)->getTable());
        $this->answers_table = DB::table((new SchlesingerSurveyQualificationAnswer)->getTable());
        $json = json_decode(
            file_get_contents("tests/Schlesinger/qualifications.json")
        );
        collect($json->qualifications)
            ->take(10)
            ->each(function (object $qualification) {

                DB::transaction(function () use ($qualification) {

                    $answers = $qualification->qualificationAnswers;
                    unset($qualification->qualificationAnswers);
                    $this->questions_table->where('qualificationId', $qualification->qualificationId)
                        ->delete();
                    $qualificationModel = SchlesingerSurveyQualificationQuestion::create(
                        array_merge((array)$qualification, ['LanguageId' => 3])
                    );

                    $qualificationModel->answers()->dd();

                });
            });

        $this->assertDatabaseCount((new SchlesingerSurveyQualificationQuestion)->getTable()
            , 10);
        $this->assertDatabaseCount((new SchlesingerSurveyQualificationAnswer)->getTable()
            , 241);

    }
}
