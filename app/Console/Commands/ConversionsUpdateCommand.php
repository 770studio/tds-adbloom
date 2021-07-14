<?php

namespace App\Console\Commands;


use App\Jobs\TuneAPIGetConversionPageJob;
use App\Models\Conversion;
use App\Services\TuneAPI\ConversionsResponse;
use App\Services\TuneAPI\TuneAPIService;
use Exception;
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
     * @throws Exception
     */
    public function handle (TuneAPIService $tuneAPIService)
    {

        $pagesCount = (new ConversionsResponse(
            $tuneAPIService->getConversions([], 1)
        ))->parseCountPages();

        for ($page = 1; $page <= $pagesCount; $page++) {
            TuneAPIGetConversionPageJob::dispatch($page, Conversion::FIELDS);
        }


    }
}
