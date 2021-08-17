<?php

namespace App\Jobs;

use App\Models\Conversion;
use App\Models\Infrastructure\RedirectStatus;
use App\Models\Infrastructure\RedirectStatus_Client;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class doPartnerPostBack implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;
    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions = 2;
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

        if (!$this->conversion->Partner) {
            Log::channel('queue')->debug('doPartnerPostBack no Partner found');

        }
        if (!$this->conversion->Opportunity) {
            Log::channel('queue')->debug('doPartnerPostBack no Opportunity found');
        }

        Log::channel('queue')->debug('doPartnerPostBack start executing',
            ['conversion id' => $this->conversion->id,
                'tune id' => $this->conversion->Stat_tune_event_id,
                'partner name' => $this->conversion->Partner->name,
                'partner external_id' => $this->conversion->Partner->external_id,
                'opportunity name' => $this->conversion->Opportunity->name,
                'opportunity external_id' => $this->conversion->Opportunity->external_id,
            ]);

//http://parner.com/?var1={eventId}&date={date}&var3={datetime}&var4={dateUpdated}&var5={datetimeUpdated}&var5={name}&var6={opportunityId}&var7={currency}&var8={payout}&var9={userPayout}&var10={points}&var11={status}&var12={token}

        $pending = false;
        $usecase = $this->conversion->Stat_status . $this->conversion->Goal_name;
        Log::channel('queue')->debug('doPartnerPostBack usecase:' . $usecase);

        if (!$macroStatus = $this->findOutStatus($usecase)) {
            Log::channel('queue')->error('unexpected compiled status:' . $usecase, $this->conversion->toArray());
            return;
        }
        Log::channel('queue')->debug('doPartnerPostBack macroStatus:' . $macroStatus);

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
            '{parnterSub2}' => $this->conversion->Stat_affiliate_info2,
            '{parnterSub3}' => $this->conversion->Stat_affiliate_info3,
            '{parnterSub4}' => $this->conversion->Stat_affiliate_info4,
            '{parnterSub5}' => $this->conversion->Stat_affiliate_info5,
            '{points}' => 1,
            '{token}' => 'token',
            '{status}' => RedirectStatus::getName($macroStatus),
            '{userPayout}' => $this->conversion->user_payout,
            '{userPoints}' => $this->conversion->user_points,


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
            if (!@$this->conversion->Partner->send_pending_status[$macroStatus]) {
                Log::channel('queue')->debug('doPartnerPostBack macroStatus is not in partner`s list', $logData);
                return;
            }
            $replaces['{status}'] = 'pending';
            Log::channel('queue')->debug('send pending 1st time', $logData);

        } elseif (!$this->conversion->partner_postback_lastsent) {
            // send one time
            Log::channel('queue')->debug('send one time', $logData);

        } else {
            // not sending anything
            Log::channel('queue')->debug('nothing to send', $logData);
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
            return RedirectStatus_Client::success;
            case 'approvedreject':
            case 'rejectedsuccess':
            return RedirectStatus_Client::reject;
            case 'approveddq':
                return RedirectStatus_Client::dq;
            case 'approvedoq':
                return RedirectStatus_Client::oq;
            default:
                return false;
            // throw new Exception('unexpected compiled status:' . $Stat_status_compiled);
        }
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {
        return [
            sprintf('doPartnerPostBack parnerId:%s conversionId:%s %s',
                $this->conversion->Partner->external_id,
                $this->conversion->id,
                app()->environment()
            ),
            $this->conversion->Stat_tune_event_id,
            'conversion:' . $this->conversion->id,
            $this->conversion->Partner->external_id,
            $this->conversion->Partner->name,
            app()->environment(),
        ];
    }


    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [new WithoutOverlapping($this->conversion->id)];
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return (string)$this->conversion->id;
    }


    /**
     * Calculate the number of seconds to wait before retrying the job.
     *
     * @return int
     */
    public function backoff()
    {
        return 30;
    }

}
