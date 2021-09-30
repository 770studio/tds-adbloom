<?php


namespace App\Services\TuneAPI;


use App\Models\Conversion;
use App\Models\ConversionsHourlyStat;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use stdClass;
use Tune\NetworkApi;
use Tune\Utils\HttpQueryBuilder;
use Tune\Utils\Operator;


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
                    (App::environment('local', 'staging')
                        ? now()->subDays(
                            config('services.tune_api.conversions_update_from_last_x_months')
                        )->toDateString()
                        : now()->subMonths(
                            config('services.tune_api.conversions_update_from_last_x_months')
                        )->toDateString()
                    )

                ]
                , null, Operator::GREATER_THAN
            )->setPage($page)
                ->setLimit(1000);

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
    public function getResponse_DEPR(Request $request): Response
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

    public function getConversionsHourlyStats(string $stat_date, int $stat_hour, int $page = 1): object
    {


        return $this->api
            ->report()
            ->getStats(function (HttpQueryBuilder $builder) use ($page, $stat_date, $stat_hour) {
                return $builder->setFields(
                    ConversionsHourlyStat::TUNE_FIELDS
                )->addFilter('Stat.date', [$stat_date]
                    , null, Operator::EQUAL_TO)
                    ->addFilter('Stat.hour', [$stat_hour]
                        , null, Operator::EQUAL_TO
                    )->setPage($page)
                    ->setLimit(1000);
            }, /* Request options */ []);
    }


}
