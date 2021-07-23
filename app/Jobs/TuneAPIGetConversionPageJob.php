<?php

namespace App\Jobs;

use App\Jobs\Middleware\TuneAPIRateLimited;
use App\Models\Conversion;
use App\Services\TuneAPI\ConversionsResponse;
use App\Services\TuneAPI\TuneAPIService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Redis\LimiterTimeoutException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class TuneAPIGetConversionPageJob implements ShouldQueue
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
    public $maxExceptions = 3;
    /**
     * @var array
     */
    private $fields;
    /**
     * @var int
     */
    private $page;
    /**
     * @var TuneAPIService
     */
    private $tuneAPIService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $page, array $fields)
    {
        $this->page = $page;
        $this->fields = $fields;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws LimiterTimeoutException|Exception
     */
    public function handle(TuneAPIService $tuneAPIService)
    {


        (new ConversionsResponse(
            $tuneAPIService->getConversions($this->fields, $this->page)
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
        return ['TuneAPIGetConversionPageJob_page#' . $this->page];
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
}
