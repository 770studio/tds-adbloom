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
    protected $signature = 'conversions:collectHourlyStats {--stat_date=} {--stat_hour=}';

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
        // auto stat sate and hour = current date last hour
        $this->stat_date = now();
        $this->stat_hour = $this->stat_date->subHour()->hour;

    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Exception
     */
    public function handle(TuneAPIService $tuneAPIService, ConversionsHourlyStatsResponse $responseProcessor)
    {
        // manual (configurable) stat_date
        $this->stat_date = $this->option('stat_date')
            ? Carbon::parse($this->option('stat_date'))
            : $this->stat_date;
        // manual (configurable) stat_hour
        $this->stat_hour = $this->option('stat_hour') !== null
            ? (int)$this->option('stat_hour')
            : $this->stat_hour;

        if (
            ConversionsHourlyStat::dateHourExists($this->stat_date, $this->stat_hour)
        ) {
            $this->line(" we have already parsed it");
            return;
        }


        $pagesCount = $responseProcessor->setData(
            $tuneAPIService->getConversionsHourlyStats($this->stat_date, $this->stat_hour, 1)
        )
            ->validate()
            ->parseCountPages();

        if (!$pagesCount) {
            $this->line(
                sprintf('no data for date:%s, hour:%d',
                    $this->stat_date, $this->stat_hour
                )
            );
        }

        for ($page = 1; $page <= $pagesCount; $page++) {
            TuneAPIGetConversionHourlyStatPageJob::dispatch($page, $this->stat_date, $this->stat_hour);
            $this->line(
                sprintf('set queued job for page:%d, date:%s, hour:%d',
                    $page, $this->stat_date, $this->stat_hour
                )
            );
        }
    }



}
