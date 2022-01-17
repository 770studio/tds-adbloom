<?php

namespace App\Console\Commands\StatsAlerts;

use App\Services\StatsAlerts\FlexPeriod;
use App\Services\StatsAlerts\StatsAlertsService;
use Illuminate\Console\Command;

/**
 * i want a alert that fires when a campaign gets 50 clicks and/or 1+ conversion
 * when it never had activity before in the last 30 days.
 * "Campaign Activated: %OfferName has received $$ clicks and $$ conversions in the last 24 hours, the first time in the previous 30 days
 */
final class TestOfferActivityCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     *
     *
     */
    protected $signature = 'statstests:alert3 {--notify}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    private FlexPeriod $recent_period;
    private FlexPeriod $older_period;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(FlexPeriod $recent_period = null, FlexPeriod $older_period = null)
    {
        parent::__construct();
        //first period is starting 30 days ago and ending day before yesterday end of the day
        $this->older_period = $older_period ?? new FlexPeriod('last30d');
        //second period is yesterday
        $this->recent_period = $recent_period ?? new FlexPeriod(1);
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(StatsAlertsService $alerts): int
    {
        $alerts->notify((bool)$this->option('notify'))
            ->testAlert3($this->recent_period, $this->older_period);

        return Command::SUCCESS;
    }




}
