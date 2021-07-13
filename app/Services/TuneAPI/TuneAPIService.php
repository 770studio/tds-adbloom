<?php


namespace App\Services\TuneAPI;


use App\Models\Conversion;
use Exception;
use Illuminate\Support\Facades\Log;
use stdClass;
use Tune\NetworkApi;
use Tune\Utils\HttpQueryBuilder;
use Tune\Utils\Operator;

//TODO rate limiter
// Networks are limited to a maximum of 50 API calls every 10 seconds. If you exceed the rate limit, your API call returns the following error: "API usage exceeded rate limit. Configured: 50/10s window; Your usage: " followed by the number of API calls you've attempted in that 10 second window.

class TuneAPIService
{


    /**
     * @var NetworkApi
     */
    public $api;
    private $entityName;

    public function __construct(NetworkApi $api)
    {
        $this->api = $api;

    }


    public function getConversions(array $fields, int $page): stdClass
    {
        return $this->api->report()->getConversions(function (HttpQueryBuilder $builder) use ($fields, $page) {
            return $builder->setFields(
            //array_slice(Conversion::FIELDS, 1,10)
                array_merge([Conversion::ID_FIELD], $fields)
            )->addFilter('Stat.datetime',
                [
                    now()->subMonths(
                        config('services.tune_api.conversions_update_from_last_x_months')
                    )->toDateString()
                ]
                , null, Operator::GREATER_THAN
            )->setPage($page);
        }, /* Request options */ []);
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
