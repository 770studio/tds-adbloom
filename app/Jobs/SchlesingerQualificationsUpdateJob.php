<?php

namespace App\Jobs;

use App\Models\SchlesingerSurveyQualificationAnswer;
use App\Models\SchlesingerSurveyQualificationQuestion;
use App\Services\SchlesingerAPI\SchlesingerAPIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Throwable;

class SchlesingerQualificationsUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $LanguageId;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;
    /**
     * The maximum number of unhandled exceptions to allow before failing.
     */
    public int $maxExceptions = 1;
    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 120;

    private Builder $answers_table;
    private Builder $questions_table;

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
     *  TODO the procedure takes several minutes might be refactored to do it in less time
     * @return void
     * @throws Throwable
     */
    public function handle(SchlesingerAPIService $Schlesinger)
    {
        $this->questions_table = DB::table((new SchlesingerSurveyQualificationQuestion)->getTable());
        $this->answers_table = DB::table((new SchlesingerSurveyQualificationAnswer)->getTable());

        $Schlesinger->getQualificationsByLangID($this->LanguageId)
            ->parseData()
            ->each(function (object $qualification) {

                DB::transaction(function () use ($qualification) {

                    $answers = $qualification->qualificationAnswers;
                    unset($qualification->qualificationAnswers);
                    $this->questions_table->where('qualificationId', $qualification->qualificationId)
                        ->delete();
                    $qualificationModel = SchlesingerSurveyQualificationQuestion::create(
                        array_merge((array)$qualification, ['LanguageId' => $this->LanguageId])
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
                    /*

                                SchlesingerSurveyQualificationAnswer::upsert(
                                            $answersChunk->toArray(),
                                            ["qualification_internalId", "answerId"],
                                            array_keys($answersChunk->first())
                                        );

                                      $qualificationModel = SchlesingerSurveyQualificationQuestion::updateOrCreate(
                                    ['LanguageId' => $this->LanguageId, 'qualificationId' => $qualification->qualificationId],
                                    (array)$qualification
                                );*/


                });
            });


    }

    /**
     * Calculate the number of seconds to wait before retrying the job.
     *
     * @return int
     */
    public function backoff()
    {
        return 60;
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {
        return [
            'Schlesinger',
            'SchlesingerQualificationsUpdateJob',
            'SchlesingerQualificationsUpdateJob_languageID#' . $this->LanguageId,

        ];
    }
}
