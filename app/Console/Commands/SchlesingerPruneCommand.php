<?php

namespace App\Console\Commands;

use App\Helpers\DisablesForeignKeys;
use App\Models\Integrations\Schlesinger;
use App\Models\SchlesingerIndustry;
use App\Models\SchlesingerSurveyQualificationAnswer;
use App\Models\SchlesingerSurveyQualificationQuestion;
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

        Schlesinger::truncate();
        SchlesingerSurveyQualificationQuestion::truncate();
        SchlesingerSurveyQualificationAnswer::truncate();
        SchlesingerIndustry::truncate();
        // supposed to only apply to a single connection and reset it's self
        // but I like to explicitly undo what I've done for clarity
        $this->enableForeignKeys();

        return 0;
    }
}
