<?php

namespace App\Console\Commands\Sclesinger;

use App\Jobs\SchlesingerSurveyQualificationsUpdateJob;
use App\Models\Integrations\Schlesinger\SchlesingerSurvey;
use Illuminate\Console\Command;

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
    public function handle()
    {
        SchlesingerSurvey::cursor()
            ->each(function ($survey) {
                SchlesingerSurveyQualificationsUpdateJob::dispatch($survey)->onQueue('schlesinger');
            });

        return Command::SUCCESS;
    }
}
