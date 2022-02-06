<?php

namespace App\Console\Commands;

use App\Models\SchlesingerIndustry;
use App\Services\SchlesingerAPI\SchlesingerAPIService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class SchlesingerIndustriesUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schlesinger-survey-industries:update';

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
     * @throws Throwable
     */
    public function handle(SchlesingerAPIService $service)
    {
        $service->getIndustries()
            ->parseData()
            ->chunk(500)
            ->each(function (Collection $industryChunk) {
                DB::transaction(function () use ($industryChunk) {
                    DB::table((new SchlesingerIndustry)->getTable())->delete();
                    SchlesingerIndustry::insert(
                        $industryChunk->toArray()
                    );
                });
            });


        return 0;
    }
}
