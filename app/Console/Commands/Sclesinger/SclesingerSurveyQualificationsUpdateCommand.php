<?php

namespace App\Console\Commands\Sclesinger;

use App\Jobs\SchlesingerSurveyQualificationsUpdateJob;
use App\Models\Integrations\Schlesinger\Schlesinger;
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
        Schlesinger::cursor()
            ->each(function ($survey) {
                SchlesingerSurveyQualificationsUpdateJob::dispatch($survey)->onQueue('Schlesinger');
            });

        return Command::SUCCESS;
    }
}
