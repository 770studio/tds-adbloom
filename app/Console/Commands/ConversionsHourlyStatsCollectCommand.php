<?php

namespace App\Console\Commands;

use App\Jobs\TuneAPIGetConversionHourlyStatPageJob;
use App\Models\ConversionsHourlyStat;
use App\Services\TuneAPI\ConversionsHourlyStatsResponse;
use App\Services\TuneAPI\TuneAPIService;
use Carbon\Carbon;
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
    private int $stat_hour;
    private Carbon $stat_date;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->stat_date = now();
        $this->stat_hour = $this->stat_date->subHour()->hour;


    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Exception
     */
    public function handle(TuneAPIService $tuneAPIService)
    {

        if (
            ConversionsHourlyStat::dateHourExists($this->stat_date, $this->stat_hour)
        ) {
            return; // we have already parsed it
        }


        $pagesCount = (new ConversionsHourlyStatsResponse(
            $tuneAPIService->getConversionsHourlyStats($this->stat_date, $this->stat_hour, 1)
        ))->parseCountPages();

        for ($page = 1; $page <= $pagesCount; $page++) {
            TuneAPIGetConversionHourlyStatPageJob::dispatch($page, $this->stat_date, $this->stat_hour);
        }
    }



}
