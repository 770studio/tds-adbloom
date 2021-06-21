<?php


namespace App\Services\TuneAPI;


use stdClass;

class Response
{
    public $pageCount;
    public $data;

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

}
