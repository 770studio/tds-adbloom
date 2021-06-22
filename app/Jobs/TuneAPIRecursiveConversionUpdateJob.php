<?php

namespace App\Jobs;

use App\Conversion;
use App\Services\TuneAPI\Request;
use App\Services\TuneAPI\TuneAPIService;
use Carbon\CarbonImmutable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Tune\Utils\Operator;

class TuneAPIRecursiveConversionUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    //const UPDATE_STARTING_FROM_LAST_X_MONTHS = 3;
    const LIMIT_PER_PAGE = 500;

    public $startId;
    public $startDateTime;

    /**
     * @param int $startId
     * @param CarbonImmutable $startDateTime
     */
    public function __construct(int $startId, CarbonImmutable $startDateTime)
    {
        $this->startId = $startId;
        $this->startDateTime = $startDateTime;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(TuneAPIService $tuneAPIService, Request $request)
    {


        $changed = $created = 0;
        $last_id = $this->startId;


        try {
            $tuneAPIService
                ->setEntity('Conversion')
                ->getResponse(
                    $request
                        ->limit(self::LIMIT_PER_PAGE)
                        ->sortBy('id')
                        ->filter(
                            'datetime', Operator::GREATER_THAN_OR_EQUAL_TO
                            , $this->startDateTime->toDateTimeString()
                        )
                        ->filter('id', Operator::GREATER_THAN, $this->startId)
                )
                ->parseData()
                ->each(function ($item) use (&$changed, &$created, &$last_id) {
                    Log::channel('queue')->debug('updateOrCreate:', [
                            'entity' => 'Conversion',
                            'id' => $item->id
                        ]
                    );

                    $res = Conversion::updateOrCreate(
                        ['id' => $item->id],
                        (array)$item
                    );

                    if ($res->wasRecentlyCreated) {
                        // insert
                        $created++;
                    } elseif ($res->wasChanged()) {
                        // update
                        $changed++;
                    }

                    $last_id = $item->id;
                });

            dump('changed/created', [$changed, $created]);
            Log::channel('queue')->debug('changed/created:', [$changed, $created]);

            // if there are no exception then run next job
            TuneAPIRecursiveConversionUpdateJob::dispatch($last_id, $this->startDateTime);

        } catch(\Exception $e) {
            // if we are done (there are no more items) it would be an exception
            // so just write it in and stop working
            dump('exception', $e->getMessage());
            Log::channel('queue')->debug('exception:' . $e->getMessage());
            return;
        }





    }
}
