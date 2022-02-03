<?php

namespace App\Console\Commands;

use App\Models\Integrations\Schlesinger;
use App\Services\SchlesingerAPI\SchlesingerAPIService;
use Illuminate\Console\Command;

class SchlesingerAllocatedSurveysUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schlesinger-surveys:update';

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

        /*        DB::listen(function ($query) {
                    $sql = $query->sql;
                    echo( Str::replaceArray('?', $query->bindings, $sql) );
                    dump("-----");
                });*/

        $service->getSurveys()
            ->parseData()
            ->each(function ($record) {
                Schlesinger::updateOrCreate(
                    ["SurveyId" => $record->SurveyId],
                    (array)$record
                );
            });

        return Command::SUCCESS;
    }
}
