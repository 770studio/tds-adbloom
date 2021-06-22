<?php


namespace App\Services\TuneAPI;


use Illuminate\Support\Collection;
use stdClass;

class Response
{
    private $pageCount;
    private $data;
    private $count;

    /**
     * @throws \Exception
     */
    public function __construct(stdClass $apiResult, string $entity)
    {
        if ($apiResult->response->errorMessage) throw new \Exception($apiResult->response->errorMessage);

        $this->pageCount = $apiResult->response->data->pageCount;
        $this->data = collect($apiResult->response->data->data)
            ->transform(function ($item, $key) use ($entity) {
                return $item->{$entity};
            });


    }

    public function parseData() : Collection
    {
        return $this->data;
    }

    public function parseCountPages() : int
    {
        return $this->pageCount;
    }
    public function parseCount() : int
    {
        return $this->count;
    }



}
