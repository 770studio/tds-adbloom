<?php

namespace App\Console\Commands\Sclesinger;

use App\Helpers\DisablesForeignKeys;
use App\Models\Integrations\Schlesinger\SchlesingerIndustry;
use App\Models\Integrations\Schlesinger\SchlesingerSurvey;
use App\Models\Integrations\Schlesinger\SchlesingerSurveyQualification;
use App\Models\Integrations\Schlesinger\SchlesingerSurveyQualificationAnswer;
use App\Models\Integrations\Schlesinger\SchlesingerSurveyQualificationQuestion;
use Illuminate\Console\Command;

class SchlesingerPruneCommand extends Command
{
    use DisablesForeignKeys;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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

        //disable foreign key check for this connection before running seeders
        $this->disableForeignKeys();

        SchlesingerSurvey::truncate();
        SchlesingerSurveyQualificationQuestion::truncate();
        SchlesingerSurveyQualificationAnswer::truncate();
        SchlesingerIndustry::truncate();
        SchlesingerSurveyQualification::truncate();

        // supposed to only apply to a single connection and reset it's self
        // but I like to explicitly undo what I've done for clarity
        $this->enableForeignKeys();

        return 0;
    }
}
