<?php

namespace App\Console\Commands;

use App\Jobs\TuneAPIGetConversionPageJob;
use App\Models\Conversion;
use App\Services\TuneAPI\TuneAPIService;
use Exception;
use Illuminate\Console\Command;

class ConversionsHourlyStatsCollectCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'conversions:collectHourlyStats';

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
     * @throws Exception
     */
    public function handle(TuneAPIService $tuneAPIService)
    {

        $pagesCount = (new ConversionsHourlyStatsResponse(
            $tuneAPIService->getConversionsHourlyStats([], 1)
        ))->parseCountPages();
        dd($pagesCount);


        for ($page = 1; $page <= $pagesCount; $page++) {
            TuneAPIGetConversionPageJob::dispatch($page, Conversion::FIELDS);
        }
        return 0;
    }
}
