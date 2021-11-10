<?php


namespace App\Services\GeneralResearchAPI;


use App\Interfaces\GeneralResearchAPIServiceIF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeneralResearchAPIService implements GeneralResearchAPIServiceIF
{
    public array $params = [];
    private string $api_url;


    public function __construct(Request $request, $n_bins = 3)
    {
        $this->api_url = config('services.generalresearch.api_base_url');
        $this->params = [
            'bpuid' => 'max',
            'format' => 'json',
            'ip' => '69.253.144.82', //$request->ip(),
            'n_bins' => $n_bins,
        ];
    }

    public function request(): object
    {

        $params = http_build_query(
            $this->params
        );

        $url = $this->api_url . '?' . $params;
        $response = Http::get($url);
        return $response->object();


    }
}
