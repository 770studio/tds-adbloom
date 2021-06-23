<?php

namespace App\Console\Commands;

use App\Jobs\TuneAPIRecursiveConversionUpdateJob;
use App\Services\TuneAPI\TuneAPIService;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

class ConversionsUpdateCommand extends Command
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
     * @return void
     * @throws \Exception
     */
    public function handle()
    {

        TuneAPIRecursiveConversionUpdateJob::dispatch( 0,
            CarbonImmutable::now()->subMonths(3)
        );

    }
}
