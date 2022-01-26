<?php

namespace App\Jobs;

use App\Jobs\Middleware\TuneAPIRateLimited;
use App\Models\ConversionsHourlyStat;
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
    private int $page;
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
    public function handle(TuneAPIService $tuneAPIService): void
    {
        $datetime = clone($this->stat_date);
        $datetime->hour = $this->stat_hour;
        $datetime->minute = $datetime->second = 0;
        $response = $tuneAPIService->getConversionsHourlyStats($this->stat_date, $this->stat_hour, $this->page) // get data with tune api (response data)
        ->validate(); // validate api response
        // mass insert into stats
        ConversionsHourlyStat::insert(
        // set data to further process it
            $response->parseData() // parse api response,  map it with our formatting
            // add StatDateTime based on Stat_date and Stat_hour
            ->transform(function (array $item) use ($datetime) {
                return array_merge($item,
                    ['StatDateTime' => $datetime->toDateTimeString()]
                );
            })
                ->toArray()  // insert accepts array
        );

        $this->total_count = $response->getCount();
        Log::channel('tune_hourly_data_extractor')->debug(
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
