<?php

namespace App\Services\SchlesingerAPI;

use Illuminate\Support\Facades\Http;

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
