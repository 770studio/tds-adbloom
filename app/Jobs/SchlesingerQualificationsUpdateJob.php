<?php

namespace App\Jobs;

use App\Models\SchlesingerSurveyQualificationAnswer;
use App\Models\SchlesingerSurveyQualificationQuestion;
use App\Services\SchlesingerAPI\SchlesingerAPIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class SchlesingerQualificationsUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $LanguageId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $LanguageId)
    {
        $this->LanguageId = $LanguageId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SchlesingerAPIService $Schlesinger)
    {
        $Schlesinger->getQualificationsByLangID($this->LanguageId)
            ->parseData()
            ->each(function (object $record) {
                $record = (array)$record;
                $answers = Arr::pull($record, "qualificationAnswers");
                /** @var SchlesingerSurveyQualificationQuestion $qualification */
                $qualification = SchlesingerSurveyQualificationQuestion::updateOrcreate(
                    ["LanguageId" => $this->LanguageId, "qualificationId" => Arr::get($record, "qualificationId")],
                    $record
                );

                collect($answers)
                    ->chunk(500)
                    ->transform(function ($item) use ($qualification) {
                        $item->qualification_internalId = $qualification->id;
                        return $item;
                    })
                    ->each(function (Collection $answersChunk) {
                        SchlesingerSurveyQualificationAnswer::upsert(
                            $answersChunk->toArray(),
                            ["qualification_internalId", "answerId"],
                            array_keys((array)$answersChunk->first())
                        );
                        /*
                                              SchlesingerSurveyQualificationAnswer::updateOrcreate(
                                                  ["qualification_internalId" => $question->id, "answerId" => $record->answerId ],
                                                  $record
                                              );*/
                    });


                /*           SchlesingerSurveyQualificationQuestion::upsert(
                               $records->toArray() ,
                               ['LanguageId', 'qualificationId'],
                               ['name', 'text', 'qualificationCategoryId', 'qualificationTypeId', 'qualificationCategoryGDPRId' ]
                           );*/
            });


    }
}
