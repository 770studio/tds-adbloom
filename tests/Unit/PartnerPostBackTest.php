<?php

namespace Tests\Unit;

use App\Jobs\doPartnerPostBack;
use App\Jobs\doPostBackJob;
use App\Models\Conversion;
use App\Models\Infrastructure\PartnerPostback;
use App\Models\Infrastructure\RedirectStatus;
use App\Models\Infrastructure\RedirectStatus_Client;
use ErrorException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase_MySql;
use TiMacDonald\Log\LogFake;


class PartnerPostBackTest extends TestCase_MySql
{
    public function test_conversion1_primary()
    {


        Log::swap(new LogFake);

        doPartnerPostBack::dispatch(
            Conversion::find(1)
        );

        Log::channel('queue')->assertLoggedMessage('debug', 'doPartnerPostBack no Partner found');
        Log::channel('queue')->assertLoggedMessage('debug', 'doPartnerPostBack no Opportunity found');
        Log::channel('queue')->assertLoggedMessage('debug', 'Stat_source != widget. Bye for now...');

    }

    public function test_conversion1_secondary()
    {


        Log::swap(new LogFake);

        doPartnerPostBack::dispatch(
            Conversion::find(1), true
        );

        Log::channel('queue')->assertLoggedMessage('debug', 'doPartnerPostBack no Partner found');
        Log::channel('queue')->assertLoggedMessage('debug', 'doPartnerPostBack no Opportunity found');
        Log::channel('queue')->assertLoggedMessage('debug', 'Stat_source != widget. Bye for now...');

    }

    public function test_conversion278407_primary()
    {
        // $this->withoutExceptionHandling();
        Log::swap(new LogFake);
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('Attempt to read property "name" on null');
        doPartnerPostBack::dispatch(
            Conversion::find(278407)
        );


    }

    public function test_conversion504734_primary()
    {
        Log::swap(new LogFake);
        Queue::fake();

        (new PartnerPostback)->setConversion(Conversion::find(504734))
            ->send();

        Log::channel('queue')->assertLoggedMessage('debug', 'doPartnerPostBack start executing');

        Queue::assertPushed(doPostBackJob::class);

    }

    public function test_504734()
    {
        $this->conversion = Conversion::find(504734);

        $pending = false;
        $usecase = $this->conversion->Stat_status . $this->conversion->Goal_name;

        $this->assertEquals("approvedSuccess", $usecase);
        $macroStatus = $this->findOutStatus($usecase);
        $this->assertEquals("success", $macroStatus);

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

        $this->assertTrue($this->conversion->Partner->send_pending_status[$macroStatus]);

        $url = str_replace(
            array_keys($replaces), $replaces,
            $this->conversion->Partner->pending_url
        );

        $this->assertEquals(
            'https://rewards.uat.telluride.transformco.com/tellurideAS/events/AdbloomWebHook/?access_token=7ab20d21771b1e81bc5bae84cc89b913&currency=USD&datetime=2022-02-18%2021%3A18%3A48&datetimeUpdated=2022-02-20%2016%3A32%3A52&eid=3506a59d-4615-4c50-8e60-dfef00908782&payout=0.25000&point=0&status=approved&surveyId=401&surveyName=Life%20%26%20Work%20Survey&uid=1AxjLhKYuP0BHBQWeDJvdzjHQxAiMIGOvEq9k6hmBbI%3D'
            , $url);

        $this->assertFalse($pending);

    }

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

    public function test_504734_refactored()
    {

    }
}
