<?php

namespace App\Jobs;

use App\Jobs\Middleware\TuneAPIRateLimited;
use App\Models\Conversion;
use App\Services\TuneAPI\ConversionsHourlyStatsResponse;
use App\Services\TuneAPI\TuneAPIService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Redis\LimiterTimeoutException;

class TuneAPIGetConversionHourlyStatPageJob
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;
    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions = 1;
    /**
     * @var int
     */
    private $page;
    /**
     * @var TuneAPIService
     */
    private $tuneAPIService;
    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    private Carbon $stat_date;
    private int $stat_hour;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $page, Carbon $stat_date, int $stat_hour)
    {
        $this->page = $page;
        $this->stat_date = $stat_date;
        $this->stat_hour = $stat_hour;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws LimiterTimeoutException|Exception
     */
    public function handle(TuneAPIService $tuneAPIService)
    {


        (new ConversionsHourlyStatsResponse(
            $tuneAPIService->getConversionsHourlyStats($this->stat_date, $this->stat_hour, $this->page)
        ))
            ->parseData()
            ->each(function ($record) use (&$created, &$changed) {
                Conversion::updateOrCreate(
                    ["Stat_tune_event_id" => $record["Stat_tune_event_id"]],
                    $record
                );


            });


    }


    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {
        return [
            'TuneAPIGetConversionHourlyStatPageJob_page#' . $this->page,
            //'TuneAPIGetConversionPageJob_' . app()->environment(),
            //app()->environment()
        ];
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
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [new TuneAPIRateLimited];
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return (string)$this->page;
    }
}
