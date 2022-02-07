<?php

namespace App\Console\Commands\Sclesinger;

use App\Jobs\SchlesingerSurveyQualificationsUpdateJob;
use App\Models\Integrations\Schlesinger;
use App\Services\SchlesingerAPI\SchlesingerAPIService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SclesingerSurveyQualificationsUpdateCommand extends Command
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
    public function handle(SchlesingerAPIService $service)
    {
        DB::table((new Schlesinger)->getTable())
            ->selectRaw('distinct (SurveyId)')
            ->get()
            ->map(function ($values) { // flatten it
                return current($values);
            })
            ->each(function ($SurveyId) {
                SchlesingerSurveyQualificationsUpdateJob::dispatch($SurveyId)->onQueue('Schlesinger');
            });
        return Command::SUCCESS;
    }
}
