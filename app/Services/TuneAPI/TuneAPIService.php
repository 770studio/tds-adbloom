<?php


namespace App\Services\TuneAPI;


use App\Conversion;
use App\Jobs\TuneAPIGetOneConversionJob;
use Illuminate\Support\Collection;

class TuneAPIService
{


    public function updateConversions(): void
    {
        $uptoDate = now()->subMonth(3)->toDateTimeString();
        $this->http_get(
            (new TuneAPIRequest())->findAll($uptoDate)
        )->map(function ($item, $id) {
            TuneAPIGetOneConversionJob::dispatch($id);
        });

    }

    private function http_get(string $urlRequest): Collection
    {
        //TODO curl or guzzle

        return $this->parse_response(
            json_decode(
                file_get_contents($urlRequest)
            )
        );


    }

    private function parse_response($json): Collection
    {
        $collection = collect($json->response->data);
        return $collection->count() > 1
            ? $collection
            : collect($collection->first());
    }

    public function updateSingleConversion(int $id): void
    {
        $conversion = $this->http_get(
            (new TuneAPIRequest())->findById($id)
        ) ;

             Conversion::updateOrCreate(
                   $conversion->only("id")->toArray(),
                   $conversion->toArray()
                );


    }


}
