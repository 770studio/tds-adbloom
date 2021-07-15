<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class doPostBackJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    private $url;
    /**
     * @var bool
     */
    private $pending;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $url, bool $pending = false)
    {
        $this->url = $url;
        $this->pending = $pending;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Http::withOptions([
            'debug' => true,
        ])->get($this->url);

        Log::channel('queue')->debug($this->pending
            ? 'doPendingPostBack:'
            : 'doPostBack:'
            . $this->url . PHP_EOL . $response->body());


    }
}
