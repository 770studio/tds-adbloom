<?php


namespace App\Services\TuneAPI;


use App\Models\Conversion;
use Exception;
use Illuminate\Support\Facades\Log;
use Tune\NetworkApi;
use Tune\Networks;
use Tune\Tune;
use Tune\Utils\Network;

//TODO rate limiter
// Networks are limited to a maximum of 50 API calls every 10 seconds. If you exceed the rate limit, your API call returns the following error: "API usage exceeded rate limit. Configured: 50/10s window; Your usage: " followed by the number of API calls you've attempted in that 10 second window.

class TuneAPIService
{
    const UPDATE_STARTING_FROM_LAST_X_MONTHS = 3;
    const LIMIT_PER_PAGE = 500;

    /**
     * @var NetworkApi
     */
    public $api;
    private $entityName;
    private $entity;

    public function __construct()
    {

        $this->api = Tune::networkApi(new Networks([
            new Network(
                config('services.tune_api.key'),
                config('services.tune_api.network_id')
            ), // Auto selected network
        ]));

    }



    public function setEntity($entityName): self
    {
        $this->entityName = $entityName;
        switch ($entityName) {
            case 'Conversion':
                $this->entity = new Conversion;
                break;
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    public function getResponse(Request $request): Response
    {
        dump($request->toArray());
        Log::channel('queue')->debug('request:', $request->toArray());

        switch ($this->getEntityName()) {
            case 'Conversion':
                return new Response(
                    $this->api
                        ->conversion()
                        ->findAll($request->toArray(), /* Request options */ [])
                    , $this->entityName
                );
        }


    }

    private function getEntityName()
    {
        return $this->entityName;
    }





}
