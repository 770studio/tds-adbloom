<?php


namespace App\Services\GeneralResearchAPI;


use App\Exceptions\BreakingException;
use App\Jobs\doPostBackJob;
use App\Models\Infrastructure\RedirectStatus_Client;
use App\Models\Partner;
use App\Models\Widget;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeneralResearchAPIService
{
    public array $params = [];
    private string $api_url;
    private int $timeout;
    private Partner $partner;
    private Widget $widget;
    private Request $request;


    public function __construct(Request $request, $n_bins = 1)
    {
        $this->request = $request;

        $this->api_url = sprintf("%s/%s/offerwall/45b7228a7/",
            config('services.generalresearch.api_base_url'),
            config('services.generalresearch.api_key')
        );

        $this->timeout = config('services.common_api.timeout');

        $this->params = [
            'bpuid' => $request->userId ?? 'generic',
            'format' => 'json',
            'ip' => $request->ip(), // '69.253.144.82'
            'country_iso' => $request->country,
            'min_payout' => 1,
            'n_bins' => $n_bins,

            /*age
            zip
            gender*/
        ];


    }

    public function getPartner(): Partner
    {
        return $this->partner;
    }

    public function setPartner(Partner $partner): self
    {
        $this->partner = $this->overridePartner() ?? $partner;
        return $this;
    }

    public function setWidget(Widget $widget): self
    {
        $this->widget = $widget;
        $this->setPartner($widget->partner);
        return $this;
    }

    /**
     * @throws Exception
     */
    public function makeRequest(): object
    {
        $params = http_build_query(
            array_merge($this->params, [
                'clickId' => $this->getClickID(),
                'widgetId' => $this->widget->short_id
            ])
        );

        $url = $this->api_url . '?' . $params;

        Log::channel('queue')->debug('grl api request:' . $url);

        $resp_object = Http::timeout($this->timeout)
            ->get($url)
            ->object();
        if (!$resp_object) {
            throw new BreakingException('external api can not be reached, 500 or smth...');
        }

        return $resp_object;

    }

    /*
     * Before the GRL Proxy API call we need to get a Click ID from Tune.
     * To do so call this endpoint https://trk.adbloom.co/aff_c?&aff_id={partnerId}&offer_id=389&aff_click_id={clickId}&aff_sub5={userId}&aff_unique2={birthdate}&aff_unique3={email}&aff_sub2={country}&aff_sub4={gender}&source=widget&format=json. Replace partnerId with correct ID the request was made with.
     * Example request https://trk.adbloom.co/aff_c?&aff_id=2&offer_id=389&format=json (without extra params).
     */
    private function getClickID(): string
    {

        $data = [
            'aff_click_id' => $this->request->get('clickId'),
            'aff_unique1' => $this->request->get('userId'),
            'aff_unique2' => $this->request->get('birthdate'),
            'aff_unique3' => $this->request->get('email'),
            'aff_sub2' => $this->request->get('country'),
            'aff_sub4' => $this->request->get('gender'),
            'aff_sub5' => $this->widget->short_id,
            'source' => 'widget',
            'format' => 'json',
            'offer_id' => '389',
            'aff_id' => $this->partner->external_id,
        ];


        return Http::timeout($this->timeout)
            ->get("https://trk.adbloom.co/aff_c?" . http_build_query($data))
            ->object()
            ->response->data->transaction_id; // выкинет иксепшн и дальше не пойдем


    }

    /**
     * @throws Exception
     *
     */
    public function sendStatusToTune(string $tsid): string
    {

        $url = sprintf("%s/%s/status/%s/",
            config('services.generalresearch.api_base_url'),
            config('services.generalresearch.api_key'),
            $tsid
        );

        Log::channel('queue')->debug('grl status request:' . $url);

        $resp_object = Http::timeout($this->timeout)
            ->get($url)
            ->object();
        if (!$resp_object) {
            throw new BreakingException('external api can not be reached, 500 or smth...');
        }
        if (!isset($resp_object->status)) {
            throw new BreakingException('external api status can not be read');
        }

        Log::channel('queue')->debug('grl status reply:' . json_encode($resp_object));

        $back_url = 'none';
        /*
         *   If status=3 the survey is successful, send a conversion to Tune
         *   If status=2 the survey is rejected, send a conversion to Tune (goal_id=389)
         */
        Log::channel('queue')->debug('status:' . $resp_object->status);

        switch ($resp_object->status) {
            //TODO refactor to kind of SendToTune helper/service/factory or a model method
             case "3":
                 if (!isset($resp_object->kwargs->clickId)) {
                     throw new BreakingException('external api data (clickId) can not be read');
                 }
                 $back_url = sprintf("https://trk.adbloom.co/aff_lsr?transaction_id=%s&amount=%s&adv_sub=%s",
                     $resp_object->kwargs->clickId,
                     number_format(optional($resp_object)->payout / 100, 2, '.', ''), // in dollars
                     $resp_object->tsid ?? null
                 );
                 doPostBackJob::dispatch($back_url)->onQueue('send_to_tune');
                 return RedirectStatus_Client::success;
                 break;
            case "2":
                $back_url = sprintf("https://trk.adbloom.co/aff_goal?a=lsr&goal_id=%d&transaction_id=%s&adv_sub=%s",
                    153,
                    $resp_object->kwargs->clickId ?? null,
                    $resp_object->tsid ?? null

                );
                doPostBackJob::dispatch($back_url)->onQueue('send_to_tune');

                break;

            default:
                throw new BreakingException('sendStatusToTune: wrong status:' . $resp_object->status);
        }

        return RedirectStatus_Client::reject;

        //var_dump($resp_object->status, $back_url);

    }

    private function overridePartner(): ?Partner
    {
        // partner is either in partnerId of the request or related to widget
        if (request()->partnerId && $partner = Partner::where('external_id', request()->partnerId)->first()) {
            Log::channel('queue')->debug('partner is overridden:' . $partner->external_id);
            return $partner;
        }
        return null;
    }


}
