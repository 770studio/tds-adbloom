<?php

namespace App\Jobs;

use App\Services\TuneAPI\TuneAPIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TuneAPIGetOneConversionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $conversion_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $conversion_id)
    {
        $this->conversion_id = $conversion_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(TuneAPIService $tuneAPIService)
    {
        $tuneAPIService->updateSingleConversion($this->conversion_id); // 1723213

    }
}
