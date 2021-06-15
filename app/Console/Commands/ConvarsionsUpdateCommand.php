<?php

namespace App\Console\Commands;

use App\Services\TuneAPI\TuneAPIService;
use Illuminate\Console\Command;

class convarsionsUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'conversions:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(TuneAPIService $tuneAPIService)
    {
        //TuneAPIGetOneConversionJob::dispatch(1723221);
        // return;

        try {
            $tuneAPIService->updateConversions();

        } catch (\Exception $e) {
            //TODO exception handling
        }

    }
}
