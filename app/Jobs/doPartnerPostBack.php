<?php

namespace App\Jobs;

use App\Models\Conversion;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        if(!$this->conversion->Partner || !$this->conversion->Opportunity) return;
        if(!$this->conversion->Partner->send_pending_postback ) return;



//http://parner.com/?var1={eventId}&date={date}&var3={datetime}&var4={dateUpdated}&var5={datetimeUpdated}&var5={name}&var6={opportunityId}&var7={currency}&var8={payout}&var9={userPayout}&var10={points}&var11={status}&var12={token}

        $status = $this->findOutStatus(
            $this->conversion->Stat_status . $this->conversion->Goal_name
        );

        if(!@$this->conversion->Partner->send_pending_status[$status]) return;

        $replaces = [
            '{eventId}' =>  $this->conversion->Stat_tune_event_id,
            '{date}' =>  $this->conversion->created_at->toDateString(),
            '{datetime}' =>  $this->conversion->created_at->toDateTimeString(),
            '{dateUpdated}' =>  $this->conversion->updated_at->toDateString(),
            '{datetimeUpdated}' =>  $this->conversion->updated_at->toDateTimeString(),
            '{name}' =>  $this->conversion->Opportunity->name,
            '{opportunityId}' =>  $this->conversion->Opportunity->id,
            '{currency}' =>  $this->conversion->Stat_currency,
            '{payout}' =>  $this->conversion->Stat_payout,
            '{userPayout}' =>  1,
            '{point}' =>  1,
            '{token}' =>  'token',
            '{status}' => $status
        ,
        ];

        $url = str_replace(
            array_keys($replaces), $replaces,
             $this->conversion->Partner->pending_url
        );



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


        doPostBackJob::dispatch(
            $url
        )->onQueue('postback_queue');
    }

    /**
     * @throws Exception
     */
    private function findOutStatus(string $Stat_status_compiled)
    {

        switch($Stat_status_compiled)
        {
            case 'approvedDefault':
            case 'approved':
                return 'success';
            case 'approvedSuccess':
                return 'pending';
            case 'approvedReject':
            case 'rejectedSuccess':
                return 'reject';
            case 'approvedDQ':
                return 'dq';
            case 'approvedOQ':
                return 'oq';
                default:
                    throw new Exception('unexpected compiled status:' . $Stat_status_compiled);
        }
    }
}
