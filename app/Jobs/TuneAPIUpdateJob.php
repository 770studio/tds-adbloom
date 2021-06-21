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

    public $apiRequest;
    public $entityName;
    /**
     * TuneAPIUpdateJob constructor.
     * @param array $apiRequest
     * @param string $entityName
     */
    public function __construct(array $apiRequest, string $entityName)
    {
        $this->apiRequest = $apiRequest;
        $this->entityName = $entityName;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle(TuneAPIService $tuneAPIService)
    {

        $response = $tuneAPIService->setEntity($this->entityName)
                                    ->getData($this->apiRequest);

        $tuneAPIService->processPage($response->data);
    }
}
