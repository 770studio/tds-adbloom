<?php


namespace App\Services\GeneralResearchAPI;


use Illuminate\Support\Facades\Http;

class GeneralResearchAPIService
{

    public function __construct($limit = 1000, $country = "US", $basic = 1)
    {
//https://fsb.generalresearch.com/6c7c06f784d14fb98a292cf1410169b1/offerwall/45b7228a7/?bpuid=max&format=json&ip=69.253.144.82&n_bins=3
    }

    public function BasicAPICall(): object
    {

        $params = http_build_query(
            $this->params
        );

        $url = $this->api_url . '?' . $params;

        $response = Http::withOptions(
            ['debug' => true]
        )->withHeaders([
            'X-YourSurveys-Api-Key' => $this->secret,
        ])->get($url);

        return $response->object();


    }
}
