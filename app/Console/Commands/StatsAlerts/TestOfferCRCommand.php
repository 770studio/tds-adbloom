<?php

namespace App\Console\Commands\StatsAlerts;

use App\Services\StatsAlerts\FlexPeriod;
use App\Services\StatsAlerts\StatsAlertsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use LogicException;

/**
 * we have an offer
 * the offer has a conversion rate
 * conversioni rate = clicks/conversions
 * 10 clicks/1 convesion = 10% conversion rate
 * lets say each day, offer conversion rate is average 10%
 * on day 12
 * conversion rate is 4%
 * on day 13, conversion rate is 2%
 * conversion rate has changed by more then 50% in 24 hour period
 * we need an alert
 *
 */
final class TestOfferCRCommand extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     *
     *
     */
    protected $signature = 'statstests:alert2 {--older=} {--recent=} {--notify}';

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
        $this->logger = Log::channel('stats_alerts');
        $this->alerts = collect();
    }

    /*    public function line($string, $style = null, $verbosity = null)
        {
            $this->logger->debug($string);
            parent::line($string, $style, $verbosity);
        }*/
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(StatsAlertsService $alerts)
    {
        // but we also might need to compare a custom arbitrary period
        // TODO refactor somehow
        if ($this->option('older') !== null) {
            $older_period = (int)$this->option('older');
            $recent_period = (int)$this->option('recent');

            if (!$recent_period) {
                $recent_period = $older_period - 1;
                if ($recent_period < 0) {
                    throw new LogicException('can not use a future period');
                }
            }
        } else {
            // by default we compare last24h to prev24h
            $older_period = 'prev24h';
            $recent_period = 'last24h';
        }


        $alerts->notify((bool)$this->option('notify'))
            ->testAlert2(new FlexPeriod($recent_period), new FlexPeriod($older_period));

        return Command::SUCCESS;


    }



}
