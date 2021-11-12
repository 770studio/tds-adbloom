<?php


namespace App\Services\GeneralResearchAPI;


use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeneralResearchAPIService
{
    public array $params = [];
    private string $api_url;
    private int $timeout;
    private Partner $partner;


    public function __construct(Request $request, $n_bins = 3)
    {
        $this->api_url = config('services.generalresearch.api_base_url');
        $this->timeout = config('services.common_api.timeout');
        $this->params = [
            'bpuid' => 'max',
            'format' => 'json',
            'ip' => $request->ip(), // '69.253.144.82' , //, //TODO setup ip for tests in service provider probably
            'n_bins' => $n_bins,
            'min_payout' => 1,
        ];


    }

    public function setPartner(Partner $partner): self
    {
        $this->partner = $partner;
        return $this;
    }

    public function makeRequest(): object
    {
        $params = http_build_query(
            array_merge($this->params, [
                'clickId' => $this->getClickID()
            ])
        );

        $url = $this->api_url . '?' . $params;

        return Http::timeout($this->timeout)
            ->get($url)
            ->object();

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
