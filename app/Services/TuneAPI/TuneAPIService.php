<?php


namespace App\Services\TuneAPI;


use App\Conversion;
use App\Jobs\TuneAPIUpdateJob;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Tune\Networks;
use Tune\Tune;
use Tune\Utils\Network;
use Tune\Utils\Operator;

//TODO rate limiter
// Networks are limited to a maximum of 50 API calls every 10 seconds. If you exceed the rate limit, your API call returns the following error: "API usage exceeded rate limit. Configured: 50/10s window; Your usage: " followed by the number of API calls you've attempted in that 10 second window.

class TuneAPIService
{
    const UPDATE_STARTING_FROM_LAST_X_MONTHS = 3;
    const LIMIT_PER_PAGE = 500;

    /**
     * @var \Tune\NetworkApi
     */
    public $api;
    private $entityName;
    private $entity;

    public function __construct()
    {

        $this->api = Tune::networkApi(new Networks([
            new Network('NETzcmyVZWooz2oPYnlWDzOu9kiQmD', 'adbloom'), // Auto selected network
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
     * @throws \Exception
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

    private function getEntity()
    {
        return $this->entity;
    }

    private function sendToQueue_DEPR(array $request, int $pageCount)
    {
        if ($pageCount < 2) return;
        for ($p = $pageCount; $p > 1; $p--) {
            Log::channel('queue')->debug('queuing page:', [$p]);

            TuneAPIUpdateJob::dispatch(
                array_merge($request, ['page' => $p]),
                $this->entityName
            );
        }
    }

    public function processPage_DEPR(Collection $items)
    {

        $changed = $created = 0;
        $items->each(function ($item) use (&$changed, &$created) {
            Log::channel('queue')->debug('updateOrCreate:', [
                    'entity' => $this->getEntityName(),
                    'id' => $item->id
                ]
            );

            $r = $this->getEntity()::updateOrCreate(
                ['id' => $item->id],
                (array)$item
            );

            if ($r->wasRecentlyCreated) {
                // был инсерт
                $created++;
            } elseif ($r->wasChanged()) {
                // был апдейт
                $changed++;
            }
        });

        dump('changed/created', [$changed, $created]);
        Log::channel('queue')->debug('changed/created:', [$changed, $created]);

    }
    /**
     * @throws \Exception
     */
    public function updateConversions_DEPR(): void
    {

        $this->setEntity('Conversion');

        $request = (new Request())
            ->filter(
                'datetime', Operator::GREATER_THAN_OR_EQUAL_TO
                , now()->subMonths(self::UPDATE_STARTING_FROM_LAST_X_MONTHS)
                ->toDateTimeString()
                ->filter

            );


        [
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

        dump('pageCount', $response->pageCount);
        Log::channel('queue')->debug($response->pageCount);
        $this->sendToQueue($request, $response->pageCount);
        dump('process page:', 1);
        Log::channel('queue')->debug('process page:', [1]);

        $this->processPage($response->data);


    }


}
