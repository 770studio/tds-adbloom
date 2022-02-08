<?php

namespace App\Jobs;

use App\Models\Integrations\Schlesinger\SchlesingerSurveyQualificationQuestion;
use App\Services\SchlesingerAPI\SchlesingerAPIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
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
     *  TODO the procedure takes several minutes , might worth refactoring to do it in less time
     * @return void
     * @throws Throwable
     */
    public function handle(SchlesingerAPIService $Schlesinger)
    {

        $Schlesinger->getQualificationsByLangID($this->LanguageId)
            ->parseData()
            ->each(function (array $qualification) {
                DB::transaction(function () use ($qualification) {
                    SchlesingerSurveyQualificationQuestion::
                    where('qualificationId', data_get($qualification, "qualificationId"))
                        ->delete(); // related answers deleted by mysql delete cascade

                    $qualificationModel = SchlesingerSurveyQualificationQuestion::create(
                        array_merge(
                            Arr::except($qualification, "qualificationAnswers"), //remove answers
                            ['LanguageId' => $this->LanguageId]  // mixin language
                        )
                    );

                    $qualificationModel->answers()->createMany(
                        data_get($qualification, "qualificationAnswers")
                    );


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
