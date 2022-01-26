<?php

namespace App\Console\Commands;

use App\Models\Integrations\Schlesinger;
use App\Services\SchlesingerAPI\SchlesingerAPIService;
use App\Services\SchlesingerAPI\SchlesingerResponse;
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
    public function handle(SchlesingerAPIService $service, SchlesingerResponse $responseProcessor)
    {
        $responseProcessor->setData(
            $service->BasicAPICall()
        )
            ->parseData()
            ->each(function ($record) {
                Schlesinger::updateOrCreate(
                    ["SurveyId" => $record["SurveyId"]],
                    $record
                );
            });

        return Command::SUCCESS;
    }
}
