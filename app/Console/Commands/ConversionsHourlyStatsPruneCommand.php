<?php

namespace App\Console\Commands;

use App\Models\ConversionsHourlyStat;
use Illuminate\Console\Command;

class ConversionsHourlyStatsPruneCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'conversionsHourlyStats:prune';

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
        ConversionsHourlyStat::outdated()->delete();
        return 0;
    }
}
