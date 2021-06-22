<?php


namespace App\Services\TuneAPI;


use App\Conversion;
use App\Jobs\TuneAPIUpdateJob;
use Illuminate\Support\Collection;
use Tune\Networks;
use Tune\Tune;
use Tune\Utils\Network;
use Tune\Utils\Operator;


class TuneAPIService
{
    const UPDATE_STARTING_FROM_LAST_X_MONTHS = 3;
    const LIMIT_PER_PAGE = 100;

    /**
     * @var \Tune\NetworkApi
     */
    private $api;
    private $entityName;
    private $entity;

    public function __construct()
    {

        $this->api = Tune::networkApi(new Networks([
            new Network('NETzcmyVZWooz2oPYnlWDzOu9kiQmD', 'adbloom'), // Auto selected network
        ]));

    }

    /**
     * @throws \Exception
     */
    public function updateConversions(): void
    {

        $this->setEntity('Conversion');

        $request = [
            'filters' => [
                'datetime' => [
                    Operator::GREATER_THAN_OR_EQUAL_TO => now()
                        ->subMonths(self::UPDATE_STARTING_FROM_LAST_X_MONTHS)
                        ->toDateTimeString(),
                ]
            ],
            //'fields' => [],
            'limit' => self::LIMIT_PER_PAGE
        ];

        $response = $this->getData($request);

        $this->setToQueue($request, $response->pageCount);

        $this->processPage($response->data);


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
     * @throws \Exception
     */
    public function getData(array $request): Response
    {
        switch ($this->getEntityName()) {
            case 'Conversion':
                return new Response(
                    $this->api
                        ->conversion()
                        ->findAll($request, /* Request options */ [])
                    , $this->entityName
                );
        }


    }

    private function getEntityName()
    {
        return $this->entityName;
    }

    private function setToQueue(array $request, int $pageCount)
    {
        if ($pageCount < 2) return;
        for ($p = 2; $p < $pageCount; $p++) {
            TuneAPIUpdateJob::dispatch(
                array_merge($request, ['page' => $p]),
                $this->entityName
            );
        }
    }

    public function processPage(Collection $items)
    {

        $items->each(function ($item) {
            $this->getEntity()::updateOrCreate(
                ['id' => $item->id],
                (array)$item
            );

        });
    }

    private function getEntity()
    {
        return $this->entity;
    }

}
