<?php

namespace App\Console\Commands;

use App\Services\StatsAlerts\Traits\DBQueryWhereClauseExtendTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ConversionsHourlyStatsHistoryDataLoadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:grabHistory {--period=7}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private string $timezone;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->timezone = config('services.tune_api.stats_timezone');

    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // dd();
        $now = now()->timezone($this->timezone);
        $stop = now()->timezone($this->timezone)->subDays($this->option('period'));

        while ($now->toDateString() > $stop->toDateString()) {
            $now->subHour();
            $this->line(
                sprintf("collect HourlyStats for %s, hour: %d",
                    $now->toDateString(), $now->hour)
            );
            Artisan::call(
                sprintf("conversions:collectHourlyStats --stat_date=%s --stat_hour=%d",
                    $now->toDateString(), $now->hour)
            );
        }
        return 0;
    }
}
