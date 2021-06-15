<?php


namespace App\Services\TuneAPI;


class TuneAPIService
{



    public function updateConversions()
    {
        $uptoDate = now()->subMonth(3)->toDateTimeString();
        $urlRequest = (new TuneAPIRequest())->findAll($uptoDate);
        $conversions = $this->Http_get($urlRequest);
        dump($conversions);

    }


    private function Http_get(string $urlRequest)
    {
        //TODO curl or guzzle
        return file_get_contents($urlRequest);
    }

}
