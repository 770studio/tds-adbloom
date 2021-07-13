<?php

namespace App\Jobs;

use App\Models\Conversion;
use App\Services\TuneAPI\ConversionsResponse;
use App\Services\TuneAPI\Response;
use App\Services\TuneAPI\TuneAPIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;


class TuneAPIGetConversionPageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;
    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions = 1;
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
     * @throws \Illuminate\Contracts\Redis\LimiterTimeoutException
     */
    public function handle(TuneAPIService $tuneAPIService)
    {
        $this->tuneAPIService = $tuneAPIService;

// Networks are limited to a maximum of 50 API calls every 10 seconds.
// If you exceed the rate limit, your API call returns the following error: "API usage exceeded rate limit. Configured: 50/10s window; Your usage: " followed by the number of API calls you've attempted in that 10 second window.
#TODO move rate limiter to middleware
        Redis::throttle(
            config('services.tune_api.network_id')
        )->block(0)->allow(50)->every(10)->then(function () {
            //info('Lock obtained...');
            $this->jobItself();
            // Handle job...
        }, function () {
            // Could not obtain lock...
             $this->release(5);
        });



    }

    /**
     * @throws \Exception
     */
    private function jobItself()
    {
/*        DB::listen(function($query) {
dump(
    date("r") . ":" . $query->time . ":" . $query->sql . ' [' . implode(', ', $query->bindings) . ']' . PHP_EOL

);
        });*/


        (new ConversionsResponse(
            $this->tuneAPIService->getConversions($this->fields, $this->page)
        ))
            ->parseData()
            ->each(function($record) use (&$created, &$changed) {
                #TODO remove redundant log messages
/*                Log::channel('queue')->debug('updateOrCreate Conversion:', [
                        'tune_event_id' => $record["Stat_tune_event_id"]
                    ]
                );*/

                Conversion::updateOrCreate(
                    ["Stat_tune_event_id" => $record["Stat_tune_event_id"]],
                    $record
                );


            });

        //Log::channel('queue')->debug('changed/created:', [$changed, $created]);
    }
}
