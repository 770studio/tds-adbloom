<?php


namespace App\Services\GeneralResearchAPI;


use App\Jobs\doPostBackJob;
use App\Models\Infrastructure\RedirectStatus;
use App\Models\Partner;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class GeneralResearchAPIService
{
    public array $params = [];
    private string $api_url;
    private int $timeout;
    private Partner $partner;


    public function __construct(Request $request, $n_bins = 1)
    {
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

    public function setPartner(Partner $partner): self
    {
        $this->partner = $partner;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function makeRequest(): object
    {
        $params = http_build_query(
            array_merge($this->params, [
                'clickId' => $this->getClickID()
            ])
        );

        $url = $this->api_url . '?' . $params;

        $resp_object = Http::timeout($this->timeout)
            ->get($url)
            ->object();
        if (!$resp_object) {
            throw new Exception($url . ' can not be reached, 500 or smth...');
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
        $url = sprintf("https://trk.adbloom.co/aff_c?&aff_id=%d&offer_id=389&format=json",
            $this->partner->external_id
        );

        return Http::timeout($this->timeout)
            ->get($url)
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

        $resp_object = Http::timeout($this->timeout)
            ->get($url)
            ->object();
        if (!$resp_object) {
            throw new Exception($url . ' can not be reached, 500 or smth...');
        }
        if (!isset($resp_object->status)) {
            throw new Exception($url . ' status can not be read');
        }

        $resp_array = (array)$resp_object;
        $back_url = 'none';
        /*
         *   If status=3 the survey is successful, send a conversion to Tune
         *   If status=2 the survey is rejected, send a conversion to Tune (goal_id=389)
         */
        switch ($resp_object->status) {
            //TODO refactor to kind of SendToTune helper/service or a model method
            case "3":
                $back_url = sprintf("https://trk.adbloom.co/aff_lsr?transaction_id=%s&amount=%s&adv_sub=%s",
                    Arr::get($resp_array, 'kwargs.clicked_bucket'),
                    Arr::get($resp_array, 'payout'),
                    Arr::get($resp_array, 'tsid'),
                );
                doPostBackJob::dispatch($url)->onQueue('send_to_tune');
                return RedirectStatus::success;
                break;
            case "2":
                $back_url = sprintf("https://trk.adbloom.co/aff_goal?a=lsr&goal_id=%d&goal_name=%d&transaction_id=%s",
                    389,
                    $resp_object->status,
                    Arr::get($resp_array, 'kwargs.clicked_bucket'),
                );
                doPostBackJob::dispatch($url)->onQueue('send_to_tune');

                break;

            default: // TODO log
        }

        return RedirectStatus::reject;

        //var_dump($resp_object->status, $back_url);

    }
}
