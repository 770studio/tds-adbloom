<?php

namespace App\Models\Infrastructure;

use App\Jobs\doPostBackJob;
use App\Models\Conversion;
use Exception;
use Illuminate\Support\Facades\Log;

class PartnerPostback
{
    private Conversion $conversion;
    private bool $secondary = false;


    public function send()
    {

        if (!$this->conversion->Partner) {
            Log::channel('queue')->debug('doPartnerPostBack no Partner found');
            return;


        }
        if (!$this->conversion->Opportunity) {
            Log::channel('queue')->debug('doPartnerPostBack no Opportunity found');
            return;

        }

        if ($this->conversion->Stat_source !== 'widget') {
            Log::channel('queue')->debug('Stat_source != widget. Bye for now...');
            return;
        }

        Log::channel('queue')->debug('doPartnerPostBack start executing',
            ['conversion id' => $this->conversion->id,
                'tune id' => $this->conversion->Stat_tune_event_id,
                'partner name' => $this->conversion->Partner->name,
                'partner external_id' => $this->conversion->Partner->external_id,
                'opportunity name' => $this->conversion->Opportunity->name,
                'opportunity external_id' => $this->conversion->Opportunity->external_id,
            ]);


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
            && strtolower($usecase) === 'approvedsuccess'
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

    public function setConversion(Conversion $conversion): self
    {
        $this->conversion = $conversion;
        return $this;
    }

    public function setSecondary($secondary): self
    {
        $this->secondary = $secondary;
        return $this;
    }
}
