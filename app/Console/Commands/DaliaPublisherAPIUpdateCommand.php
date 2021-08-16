<?php

namespace App\Console\Commands;

use App\Interfaces\DaliaPublisherAPIServiceIF;
use App\Models\Integrations\DaliaOffers;
use Illuminate\Console\Command;

class DaliaPublisherAPIUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dalia_publisher_api:update {args?*}';

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
    public function handle(DaliaPublisherAPIServiceIF $APIService)
    {

         //dd($APIService);

            $APIService->getAll()
            ->parseData()
            ->each(function ($record) {
                DaliaOffers::updateOrCreate(
                    ["project_id" => $record["project_id"]],
                    $record
                );


            });


        return 0;
    }

}
