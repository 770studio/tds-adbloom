<?php

namespace App\Console\Commands\Sclesinger;

use App\Models\Integrations\Schlesinger;
use App\Services\SchlesingerAPI\SchlesingerAPIService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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

        DB::transaction(function () use ($service) {
            $table = (new Schlesinger)->getTable();
            DB::table($table)->delete();//TODO prune (truncate) once per e.g week
            DB::table($table)->insert(
                $service->getSurveys()
                    ->parseData()
                    ->toArray()
            );


        });

        return Command::SUCCESS;
    }
}
