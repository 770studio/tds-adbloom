<?php


namespace App\Services\DaliaPublisherAPI;


use App\Interfaces\DaliaPublisherAPIServiceIF;
use Illuminate\Support\Facades\Http;

class DaliaPublisherAPIService implements DaliaPublisherAPIServiceIF
{
    private $secret;
    private $api_url;

    public function __construct()
    {
        $this->secret = config('services.yoursurveys_readme_io.secret');
        $this->api_url = config('services.yoursurveys_readme_io.url');

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
