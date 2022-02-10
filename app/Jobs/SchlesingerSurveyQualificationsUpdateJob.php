<?php

namespace App\Jobs;

use App\Models\Integrations\Schlesinger\SchlesingerSurvey;
use App\Models\Integrations\Schlesinger\SchlesingerSurveyQualification;
use App\Models\Integrations\Schlesinger\SchlesingerSurveyQualificationQuestion;
use App\Services\SchlesingerAPI\SchlesingerAPIService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Throwable;

class SchlesingerSurveyQualificationsUpdateJob extends SchlesingerQualificationsUpdateJob implements ShouldQueue
{

    private SchlesingerSurvey $survey;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SchlesingerSurvey $survey)
    {
        $this->survey = $survey;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Throwable
     */
    public function handle(SchlesingerAPIService $Schlesinger)
    {

        $survey_internalId = $this->survey->getKey();

        SchlesingerSurveyQualification::whereSurveyInternalid($survey_internalId)
            ->delete();

        $Schlesinger->getQualificationAdmitCriteria($this->survey->SurveyId)
            ->parseData()
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

    }
}
