<?php

namespace App\Services\SchlesingerAPI;

use Illuminate\Support\Facades\Http;

/**
 * https://developer-beta.market-cube.com/api-details#api=supply-api-v2&operation=get-api-v2-survey-allocated-surveys&definition=SampleCube.SupplyAPI.Core.Models.APIResult
 */
class SchlesingerAPIService
{
    private $secret;
    private $api_url;

    public function __construct()
    {
        $this->secret = config('services.schlesinger.secret');
        $this->api_url = config('services.schlesinger.url');
    }


    public function BasicAPICall(): SchlesingerResponse
    {

        $response = Http::withOptions(
            ['debug' => true]
        )->withHeaders([
            'X-MC-SUPPLY-KEY' => $this->secret,
        ])->get($this->api_url);

        return new SchlesingerResponse(
            $response->object()
        );


    }
}
