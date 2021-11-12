<?php


namespace App\Services\GeneralResearchAPI;


use App\Models\Partner;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeneralResearchAPIService
{
    public array $params = [];
    private string $api_url;
    private int $timeout;
    private Partner $partner;


    public function __construct(Request $request, $n_bins = 1)
    {
        $this->api_url = config('services.generalresearch.api_base_url');
        $this->timeout = config('services.common_api.timeout');
        $this->params = [
            'bpuid' => $request->userId ?? 'generic',
            'format' => 'json',
            'ip' => $request->ip(), // '69.253.144.82' , //, //TODO setup ip for tests in service provider probably
            'country_iso' => $request->country,
            'min_payout' => 1,
            'n_bins' => $n_bins,

            /*age
            zip
            gender*/
        ];
        https://fsb.generalresearch.com/6c7c06f784d14fb98a292cf1410169b1/offerwall/45b7228a7/?bpuid=generic&format
        //=json&country_iso=US&ip=69.253.144.82&min_payout=1&n_bins=1&min_payout=1&

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

        //TODO partner ext id для тестов. убрать!
        // $url = "https://trk.adbloom.co/aff_c?&aff_id=2&offer_id=389&format=json";
        return Http::timeout($this->timeout)
            ->get($url)
            ->object()
            ->response->data->transaction_id; // выкинет иксепшн и дальше не пойдем


    }
}
