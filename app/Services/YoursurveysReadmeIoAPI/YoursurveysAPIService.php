<?php


namespace App\Services\YoursurveysReadmeIoAPI;


use Illuminate\Support\Facades\Http;

class YoursurveysAPIService
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

    public function BasicAPICall()
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

        dd($response->body());

        $context = stream_context_create($opts);
        /* Sends an http request to www.your-surveys.com/supppliers/surveys with additional headers shown above */
        $fp = fopen($url, 'r', false, $context);
# Output all data from the response
        fpassthru($fp);
        fclose($fp);

    }
}
