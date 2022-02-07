<?php

namespace App\Jobs;

use App\Services\SchlesingerAPI\SchlesingerAPIService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SchlesingerSurveyQualificationsUpdateJob extends SchlesingerQualificationsUpdateJob implements ShouldQueue
{
    private int $SurveyId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $SurveyId)
    {
        $this->SurveyId = $SurveyId;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SchlesingerAPIService $Schlesinger)
    {
        //
    }
}
