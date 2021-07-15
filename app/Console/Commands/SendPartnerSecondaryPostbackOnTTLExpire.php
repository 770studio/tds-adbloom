<?php

namespace App\Console\Commands;

use App\Jobs\doPartnerPostBack;
use App\Models\Conversion;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;

class SendPartnerSecondaryPostbackOnTTLExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'partner:send_pb2';

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
        Conversion::with('Partner:id,external_id,pending_timeout')
            ->whereHas('Partner')
            ->whereNotNull('pending_sent')
            ->where('partner_postbacks', '<', 2)
            ->limit(1000)
            ->each(function ($conversion) {
                if (
                    $conversion->Partner->pending_timeout <=
                    (new Carbon($conversion->pending_sent))
                        ->diffInHours(now()) / (App::environment('local', 'staging')
                        ? 1
                        : 24)// diff in hours or in days (for production)

                ) doPartnerPostBack::dispatch($conversion, true);
            });


        /*        Conversion::whereNotNull('pending_sent')
                    ->where('partner_postbacks', '<', 2)
                    ->whereHas('Partner', function($query) {

                        $query->whereRaw('SUBTIME(NOW(), pending_timeout) > conversions.pending_sent');
                })->whereDate('complains.created_at', '=', Carbon::today());
                    ->where;*/
    }
}
