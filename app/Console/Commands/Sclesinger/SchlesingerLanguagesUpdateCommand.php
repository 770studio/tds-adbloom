<?php

namespace App\Console\Commands\Sclesinger;

use App\Models\Integrations\Schlesinger\SchlesingerLanguage;
use App\Services\SchlesingerAPI\SchlesingerAPIService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class SchlesingerLanguagesUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schlesinger-languages:update';

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
        $service->getLanguages()
            ->parseData()
            ->chunk(500)
            ->each(function (Collection $langChunk) {
                DB::transaction(function () use ($langChunk) {
                    DB::table((new SchlesingerLanguage)->getTable())->delete();
                    SchlesingerLanguage::insert(
                        $langChunk->toArray()
                    );
                });
            });


        return 0;
    }
}
