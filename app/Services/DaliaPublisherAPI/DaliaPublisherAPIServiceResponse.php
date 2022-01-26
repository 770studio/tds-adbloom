<?php


namespace App\Services\DaliaPublisherAPI;


use App\Services\Response;
use Exception;
use Illuminate\Support\Collection;

class DaliaPublisherAPIServiceResponse extends Response
{

    /**
     * @throws Exception
     */
    public function validate(): self
    {
        if ($this->apiResult->status != 'ok') throw new Exception("DaliaPublisherAPI returned an error:" . serialize($this->apiResult));
        return $this;
    }

    public function parseData(): Collection
    {
        return
            collect($this->apiResult->offers)
                ->transform(function ($offer, $numkey) {
                  //TODO validate UUID
                    return [
                        'uuid' => $offer->uuid,
                        'title' => $offer->title,
                        'info_short' => $offer->info_short,
                        'info' => $offer->info,
                        'json' => json_encode($offer)


                    ];

                });

    }

}
