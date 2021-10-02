<?php


namespace App\Services\TuneAPI;


use App\Models\Conversion;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use stdClass;
use Tune\NetworkApi;
use Tune\Utils\HttpQueryBuilder;
use Tune\Utils\Operator;


class TuneAPIService
{

    public NetworkApi $api;
    private string $entityName;
    private Carbon $date_start;

    public function __construct(NetworkApi $api, $dateStart)
    {
        $this->api = $api;
        $this->date_start = $dateStart;

    }


    public function getConversions(array $fields, int $page): stdClass
    {
        return $this->api->report()->getConversions(function (HttpQueryBuilder $builder) use ($fields, $page) {
            return $builder->setFields(
            //array_slice(Conversion::FIELDS, 1,10)
                array_merge([Conversion::ID_FIELD], $fields)
            )->addFilter('Stat.datetime',
                [
                    $this->date_start->toDateString()
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
