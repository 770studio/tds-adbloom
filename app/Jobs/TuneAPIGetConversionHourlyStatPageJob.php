<?php

namespace App\Jobs;

use App\Jobs\Middleware\TuneAPIRateLimited;
use App\Models\ConversionsHourlyStat;
use App\Services\TuneAPI\ConversionsHourlyStatsResponse;
use App\Services\TuneAPI\TuneAPIService;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Redis\LimiterTimeoutException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TuneAPIGetConversionHourlyStatPageJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;
    /**
     * @var int
     */
    private $page;
    private Carbon $stat_date;
    private int $stat_hour;
    private int $total_count;

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
    public function handle(TuneAPIService                 $tuneAPIService,
                           ConversionsHourlyStatsResponse $responseProcessor): void
    {
        // mass insert into stats
        ConversionsHourlyStat::insert(
        // set data to further process it
            $responseProcessor->setData(
            // get data with tune api (response data)
                $tuneAPIService->getConversionsHourlyStats($this->stat_date, $this->stat_hour, $this->page)
            )
                ->validate() // validate api response
                ->parseData() // parse api response,  map it with our formatting
                ->toArray()  // insert accepts array
        );

        $this->total_count = $responseProcessor->getCount();
        Log::debug(
            "TuneAPIGetConversionHourlyStatPageJob",
            [
                'total_count' => $this->total_count,
                'page' => $this->page,
                'stat_date' => $this->stat_date,
                'stat_hour' => $this->stat_hour,

            ]
        );


    }


    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {
        return [
            'TuneAPIGetConversionHourlyStatPageJob',
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
        return $this->page . '-' . $this->stat_date . '-' . $this->stat_hour;
    }
}
