<?php


namespace App\Services\YoursurveysReadmeIoAPI;


use App\Interfaces\YoursurveysAPIServiceIF;
use Exception;
use Illuminate\Support\Facades\Http;

class YoursurveysAPIService implements YoursurveysAPIServiceIF
{
    public $params = [];
    private $secret;
    private $api_url;

    public function __construct($limit = 1000, $country = "US", $basic = 1)
    {
        $this->secret = config('services.yoursurveys_readme_io.secret');
        $this->api_url = config('services.yoursurveys_readme_io.url');
        $this->params = [
            'limit' => $limit,
            'country' => $country,
            'basic' => $basic,
        ];
    }

    /**
     * @throws Exception
     */
    public function BasicAPICall(): YourSurveysResponse
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

        return new YourSurveysResponse(
            $response->object()
        );


    }
}
