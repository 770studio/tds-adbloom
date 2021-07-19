<?php

namespace App\Jobs;

use App\Models\Conversion;
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
     * @var bool
     */
    private $secondary;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Conversion $conversion, $secondary = false)
    {
        $this->conversion = $conversion;
        $this->secondary = $secondary;
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

        $pending = false;
        $usecase = $this->conversion->Stat_status . $this->conversion->Goal_name;
        if (!$macroStatus = $this->findOutStatus($usecase)) {
            Log::channel('queue')->error('unexpected compiled status:' . $usecase, $this->conversion->toArray());
            return;
        }

        $replaces = array_map('rawurlencode', [
            '{eventId}' => $this->conversion->Stat_tune_event_id,
            '{date}' => $this->conversion->created_at->toDateString(),
            '{datetime}' => $this->conversion->created_at->toDateTimeString(),
            '{dateUpdated}' => $this->conversion->updated_at->toDateString(),
            '{datetimeUpdated}' => $this->conversion->updated_at->toDateTimeString(),
            '{name}' => $this->conversion->Opportunity->name,
            '{opportunityId}' => $this->conversion->Opportunity->id,
            '{currency}' => $this->conversion->Stat_currency,
            '{payout}' => $this->conversion->Stat_payout,
            '{externalId}' => $this->conversion->Opportunity->external_id,
            '{partnerUniqie1}' => $this->conversion->ConversionsMobile_affiliate_unique1,
            '{partnerUniqie2}' => $this->conversion->ConversionsMobile_affiliate_unique2,
            '{partnerUniqie3}' => $this->conversion->ConversionsMobile_affiliate_unique3,
            '{partnerUniqie4}' => $this->conversion->ConversionsMobile_affiliate_unique4,
            '{partnerUniqie5}' => $this->conversion->ConversionsMobile_affiliate_unique5,
            '{parnterClickId}' => $this->conversion->ConversionsMobile_affiliate_click_id,
            '{parnterSub2}' => $this->conversion->ConversionsMobile_adv_sub2,
            '{userPayout}' => 1,
            '{points}' => 1,
            '{token}' => 'token',
            '{status}' => $macroStatus

        ]);

        $logData = [
            'usecase' => $usecase,
            'macro Status' => $macroStatus,
            'conversion' => $this->conversion->only('Stat_tune_event_id', 'Stat_affiliate_id', 'Stat_datetime'),
            'partner' => $this->conversion->Partner->toArray(),
        ];

        if ($this->secondary) {
            // send for the second time
            Log::channel('queue')->debug('send for the second time', $logData);

        } elseif ($this->conversion->Partner->send_pending_postback && !$this->conversion->partner_postback_lastsent
            && strtolower($usecase) == 'approvedsuccess'
        ) {
            // send pending 1st time
            $pending = true;
            if (!@$this->conversion->Partner->send_pending_status[$macroStatus]) return;
            $replaces['{status}'] = 'pending';
            Log::channel('queue')->debug('send pending 1st time', $logData);

        } elseif (!$this->conversion->partner_postback_lastsent) {
            // send one time
            Log::channel('queue')->debug('send one time', $logData);

        } else {
            // not sending anything
            return;
        }

        $url = str_replace(
            array_keys($replaces), $replaces,
            $this->conversion->Partner->pending_url
        );

        doPostBackJob::dispatch(
            $url, $pending
        )->onQueue('postback_queue');

        //$this->conversion->increment('partner_postbacks');
        $this->conversion->partner_postback_lastsent = now()->toDateTimeString();
        $this->conversion->partner_postbacks += 1;
        if ($pending) $this->conversion->pending_sent = now()->toDateTimeString();
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
     * @return string | false
     * @throws Exception
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
