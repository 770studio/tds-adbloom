<?php

namespace App\Jobs;

use App\Services\TuneAPI\Response;
use App\Services\TuneAPI\TuneAPIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        (new Response(
            $tuneAPIService->getConversions($this->fields, $this->page)
        ))
            ->parseData()
            ->each(function($record){
                dd($record);
            });
    }
}
