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

//dd($APIService->getAll());

            $updateTime = now();

            $APIService->getAll()
            ->parseData()
            ->chunk(500)
            ->each(function ($records) {
                DaliaOffers::upsert($records->toArray(),
                    ['uuid'],
                    ['title', 'info_short', 'info', 'json' ]);
            });

            $APIService->deleteInExistent($updateTime);


        return 0;
    }

}
