<?php


namespace App\Services\TuneAPI;


use App\Conversion;
use App\Jobs\TuneAPIGetOneConversionJob;
use App\Jobs\TuneAPIUpdateJob;
use App\Services\TuneAPI\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Tune\Networks;
use Tune\AffiliateApi;
use Tune\Utils\Network;
use Tune\Repository\NetworkRepository;
use Tune\Tune;
use Tune\NetworkApi;

use Tune\Utils\Operator;
use Tune\Utils\HttpQueryBuilder;



class TuneAPIService
{

    /**
     * @var Networks
     */
    private $networks;

    public function __construct()
    {
        $this->networks = new Networks([
            new Network('NETzcmyVZWooz2oPYnlWDzOu9kiQmD', 'adbloom'), // Auto selected network
        ]);
    }

    /**
     * @throws \Exception
     */
    public function updateConversions(): void
    {

        $fromDate = now()->subMonths(3)->toDateTimeString();

        $request = [
            'filters' => [
                'datetime' => [
                    Operator::GREATER_THAN_OR_EQUAL_TO => $fromDate,
                ]
            ],
            //'fields' => [],
            'limit'=> 2
        ];

        $response = getConversions($request);

        $this->setToQueue($request, $response->pageCount);

        $this->processPage($response->data, Conversion::class);


    }


    private function setToQueue(array $request, int $pageCount)
    {
        if($pageCount < 2) return;
        for($p = 2; $p < $pageCount; $p++) {
            TuneAPIUpdateJob::dispatch(
                array_merge($request, ['page' => $p])
            );
        }
    }

    public function processPage(Collection $items, Model $entity)
    {
        $items->each(function($item) use ($entity) {
            $entity::updateOrCreate(
                $item->only("id")->toArray(),
                $item->toArray()
            );

        });
    }

    /**
     * @throws \Exception
     */
    public function getConversions(array $request) : Response
    {
       return new Response(
            Tune::networkApi($this->networks)
                ->conversion()->findAll( $request, /* Request options */ []),
           'Conversion'
        );

    }


}
