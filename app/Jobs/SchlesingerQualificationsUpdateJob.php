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
            ->transform(function ($item) {
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
