<?php

namespace App\Console\Commands;

use App\Jobs\SchlesingerQualificationsUpdateJob;
use App\Models\Integrations\Schlesinger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SchlesingerQualificationsUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schlesinger-survey-qualifications:update';

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
        DB::table((new Schlesinger)->getTable())
            ->select('LanguageId')
            ->groupBy('LanguageId')
            ->pluck('LanguageId')
            ->each(function ($lang_id) {
                SchlesingerQualificationsUpdateJob::dispatch($lang_id)->onQueue('Schlesinger');
            });

        return Command::SUCCESS;
    }
}
