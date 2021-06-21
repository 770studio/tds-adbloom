<?php

namespace App\Jobs;

use App\Conversion;
use App\Services\TuneAPI\TuneAPIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TuneAPIUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    /**
     * @var array
     */
    public $apiRequest;

    /**
     * TuneAPIUpdateJob constructor.
     * @param $apiRequest
     */
    public function __construct($apiRequest)
    {
        $this->apiRequest = $apiRequest;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle(TuneAPIService $tuneAPIService)
    {
        $response = $tuneAPIService->getConversions($this->apiRequest);
        $tuneAPIService->processPage($response->data, Conversion::class);
    }
}
