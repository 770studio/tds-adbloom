<?php

namespace App\Console\Commands\StatsAlerts;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TestOfferCTRonHistoricalData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deepstatstests:alert2 {depth=0}';

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
    public function handle()
    {
        $depth = (int)$this->argument('depth');
        while ($depth >= 0) {

            Artisan::call("testalert:alert2",
                [
                    '--older' => $depth + 1,
                    '--recent' => $depth,
                ]

            );

            $depth--;

        }
        return 0;
    }
}
