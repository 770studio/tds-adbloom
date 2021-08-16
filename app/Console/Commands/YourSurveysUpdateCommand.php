<?php

namespace App\Console\Commands;

use App\Models\Integrations\Yoursurveys;
use App\Interfaces\YoursurveysAPIServiceIF;
use App\Services\YoursurveysReadmeIoAPI\YourSurveysResponse;
use Exception;
use Illuminate\Console\Command;

class YourSurveysUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yoursurveys:update {args?*}';

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
     * @throws Exception
     */
    public function handle(YoursurveysAPIServiceIF $yoursurveysAPIService)
    {

        //dd($yoursurveysAPIService);

        (new YourSurveysResponse(
            $yoursurveysAPIService->BasicAPICall()
        ))
            ->parseData()
            ->each(function ($record) {
                Yoursurveys::updateOrCreate(
                    ["project_id" => $record["project_id"]],
                    $record
                );


            });


        return 0;
    }
}
