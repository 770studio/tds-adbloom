<?php

namespace App\Jobs;

use App\Models\Conversion;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class doPartnerPostBack implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Conversion
     */
    private $conversion;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Conversion $conversion)
    {
        $this->conversion = $conversion;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        if (!$this->conversion->Partner || !$this->conversion->Opportunity) return;


//http://parner.com/?var1={eventId}&date={date}&var3={datetime}&var4={dateUpdated}&var5={datetimeUpdated}&var5={name}&var6={opportunityId}&var7={currency}&var8={payout}&var9={userPayout}&var10={points}&var11={status}&var12={token}


        $usecase = $this->conversion->Stat_status . $this->conversion->Goal_name;
        if (!$macroStatus = $this->findOutStatus($usecase)) {
            Log::channel('queue')->error('unexpected compiled status:' . $usecase, $this->conversion->toArray());
            return;
        }

        $replaces = [
            '{eventId}' => $this->conversion->Stat_tune_event_id,
            '{date}' => $this->conversion->created_at->toDateString(),
            '{datetime}' => $this->conversion->created_at->toDateTimeString(),
            '{dateUpdated}' => $this->conversion->updated_at->toDateString(),
            '{datetimeUpdated}' => $this->conversion->updated_at->toDateTimeString(),
            '{name}' => $this->conversion->Opportunity->name,
            '{opportunityId}' => $this->conversion->Opportunity->id,
            '{currency}' => $this->conversion->Stat_currency,
            '{payout}' => $this->conversion->Stat_payout,
            '{userPayout}' => 1,
            '{points}' => 1,
            '{token}' => 'token',
            '{status}' => $macroStatus
            ,
        ];

        array_map('urlencode', $replaces);

        if ($this->conversion->Partner->send_pending_postback && !$this->conversion->partner_postback_lastsent
            && strtolower($usecase) == 'approvedsuccess'
        ) {
            // send pending 1st time
            if (!@$this->conversion->Partner->send_pending_status[$macroStatus]) return;
            $replaces['{status}'] = 'pending';

        } elseif (!$this->conversion->partner_postback_lastsent) {
            // send one time

        } elseif ($this->conversion->Partner->pending_timeout >= (new Carbon($this->conversion->created_at))->diff(now())->days) {
            // send for the second time

        } else {
            return;
        }

        $url = str_replace(
            array_keys($replaces), $replaces,
            $this->conversion->Partner->pending_url
        );

        doPostBackJob::dispatch(
            $url
        )->onQueue('postback_queue');

        //$this->conversion->increment('partner_postbacks');
        $this->conversion->partner_postback_lastsent = now()->toDateTimeString();
        $this->conversion->save();

        /*        eventId = Stat tune event id
date = Created (just date)
datetime = Created
dateUpdated = Updated (just date)
datetimeUpdated = Updated
name = Opportunity Name (matched via external ID)
opportunityId = (opportunity ID matched via external ID)
currency = Stat currency
payout = Stat Payout
userPayout = FIXED FOR NOW
                       points = FIXED FOR NOW
                                          status = depends or Partner settings
    token = HARD CODED*/


    }

    /**
     * @throws Exception
     * @returns string | false
     */
    private function findOutStatus(string $Stat_status_compiled)
    {

        switch (strtolower($Stat_status_compiled)) {
            case 'approveddefault':
            case 'approved':
            case 'approvedsuccess':
                return 'success';
            case 'approvedreject':
            case 'rejectedsuccess':
                return 'reject';
            case 'approveddq':
                return 'dq';
            case 'approvedoq':
                return 'oq';
            default:
                return false;
            // throw new Exception('unexpected compiled status:' . $Stat_status_compiled);
        }
    }
}
