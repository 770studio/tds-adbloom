<?php

namespace Tests\Unit;

use App\Helpers\DisablesForeignKeys;
use App\Models\SchlesingerSurveyQualificationAnswer;
use App\Models\SchlesingerSurveyQualificationQuestion;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SchlesingerQualificationsUpdateTest extends TestCase
{
    use DisablesForeignKeys;

    public function load_json()
    {

        return json_decode(
            file_get_contents("tests/Schlesinger/qualifications.json"), true
        );


    }

    public function test_json_is_valid()
    {

        $json = $this->load_json();

        $this->assertEquals(true, data_get($json, "result.success"));
        $this->assertLessThan(5000, data_get($json, "result.totalCount"));
        return $json;
    }

    /**
     *
     */
    public function test_can_parse_data()
    {
        $json = $this->load_json();

        $this->assertCount(data_get($json, "result.totalCount"),
            data_get($json, "qualifications")
        );

        return data_get($json, "qualifications");
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
     *
     */
    public function test_can_add_questions_to_db()
    {
        $json = $this->load_json();
        collect(data_get($json, "qualifications"))
            ->take(10)
            ->each(function ($qualification) {
                $answers = data_get($qualification, "qualificationAnswers");
                unset($qualification["qualificationAnswers"]);
                /** @var SchlesingerSurveyQualificationQuestion $qualificationModel */
                $qualificationModel = SchlesingerSurveyQualificationQuestion::updateOrCreate(
                    ['LanguageId' => 3, 'qualificationId' => data_get($qualification, "qualificationId")],
                    (array)$qualification
                );

                collect($answers)
                    ->transform(function (array $item) use ($qualificationModel) {
                        data_set($item, "qualification_internalId", $qualificationModel->id);
                        return $item;
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

    public function load_data_into_db(): void
    {

        $json = $this->load_json();

        collect(data_get($json, "qualifications"))
            ->take(10)
            ->each(function (array $qualification) {

                DB::transaction(function () use ($qualification) {

                    $answers = data_get($qualification, "qualificationAnswers");

                    unset($qualification["qualificationAnswers"]);
                    /*                 $this->questions_table->where('qualificationId', $qualification->qualificationId)
                                         ->delete();*/

                    // SchlesingerSurveyQualificationQuestion::recreate()
                    $qualificationModel = SchlesingerSurveyQualificationQuestion::create(
                        array_merge($qualification, ['LanguageId' => 3])
                    );
                    $qualificationModel->answers()->delete();
                    $qualificationModel->answers()->createMany($answers);


                });
            });
    }


    /**
     *
     */
    public function test_can_add_to_db_the_right_way()
    {

        $this->load_data_into_db();
        $this->assertDatabaseCount((new SchlesingerSurveyQualificationQuestion)->getTable()
            , 10);
        $this->assertDatabaseCount((new SchlesingerSurveyQualificationAnswer)->getTable()
            , 241);

    }

    public function test_answers_relation_is_ok()
    {
        $this->load_data_into_db();
        $qualificationModel = SchlesingerSurveyQualificationQuestion::first();
        $this->assertEquals(17, $qualificationModel->answers()->count());

        $this->assertEmpty(
            array_diff([
                "id" => "1",
                "qualification_internalId" => "1",
                "answerId" => "2572",
                "text" => "Newspaper",
                "answerCode" => "1",
            ], $qualificationModel->answers->first()->toArray())
        );

    }

    public function test_can_delete_related_answers()
    {

        $this->load_data_into_db();
        $qualificationModel = SchlesingerSurveyQualificationQuestion::first();
        $this->assertEquals(17, $qualificationModel->answers()->count());

        $id = $qualificationModel->id;
        $qualificationModel->delete();

        $this->assertSame(DB::table((new SchlesingerSurveyQualificationQuestion())->getTable())
            ->where('id', $id)
            ->count(), 0);
        $this->assertSame(DB::table((new SchlesingerSurveyQualificationAnswer())->getTable())
            ->where('qualification_internalId', $id)
            ->count(), 0);


    }

    public function test_prune()
    {
        $this->load_data_into_db();
        $this->assertDatabaseCount((new SchlesingerSurveyQualificationQuestion)->getTable(), 10);

        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::statement('PRAGMA foreign_keys = ON;');
        $this->disableForeignKeys();

        SchlesingerSurveyQualificationQuestion::truncate();
        $this->enableForeignKeys();
        // supposed to only apply to a single connection and reset it's self
        // but I like to explicitly undo what I've done for clarity
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // DB::statement('PRAGMA foreign_keys = ON;');


        $this->assertDatabaseCount((new SchlesingerSurveyQualificationQuestion)->getTable(), 0);

    }
}
