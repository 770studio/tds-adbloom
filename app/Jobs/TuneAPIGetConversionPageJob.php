<?php

namespace App\Jobs;

use App\Models\Conversion;
use App\Services\TuneAPI\Response;
use App\Services\TuneAPI\TuneAPIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TuneAPIGetConversionPageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var array
     */
    private $fields;
    /**
     * @var int
     */
    private $page;

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
     */
    public function handle(TuneAPIService $tuneAPIService)
    {

        $created = $changed = 0;
        (new Response(
            $tuneAPIService->getConversions($this->fields, $this->page)
        ))
            ->parseData()
            ->each(function($record) use (&$created, &$changed) {
                #TODO remove redundant log messages
                Log::channel('queue')->debug('updateOrCreate Conversion:', [
                        'tune_event_id' => $record["Stat_tune_event_id"]
                    ]
                );

                $res = Conversion::updateOrCreate(
                    ["Stat_tune_event_id" => $record["Stat_tune_event_id"]],
                    $record
                );

                if ($res->wasRecentlyCreated) {
                    // insert
                    $created++;
                } elseif ($res->wasChanged()) {
                    // update
                    $changed++;
                }
            });

        dump('changed/created', [$changed, $created]);
        Log::channel('queue')->debug('changed/created:', [$changed, $created]);

    }
}
