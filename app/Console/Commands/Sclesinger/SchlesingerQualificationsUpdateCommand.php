<?php

namespace App\Console\Commands\Sclesinger;

use App\Jobs\SchlesingerQualificationsUpdateJob;
use App\Models\Integrations\Schlesinger\Schlesinger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SchlesingerQualificationsUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schlesinger-qualifications:update';

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
        // get all languages
        DB::table((new Schlesinger)->getTable())
            ->selectRaw('distinct (LanguageId)')
            ->get()
            ->map(function ($values) {
                return current($values);
            })
            ->each(function ($lang_id) {
                SchlesingerQualificationsUpdateJob::dispatch($lang_id)->onQueue('Schlesinger');
            });

        return Command::SUCCESS;
    }
}
