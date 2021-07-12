<?php


namespace App\Services\TuneAPI;


use Illuminate\Support\Collection;
use stdClass;

class Response
{
    private $pageCount;
    private $data;
    private $count;
    private $apiResult;

    /**
     * @throws \Exception
     */
    public function __construct(stdClass $apiResult)
    {
        if ($apiResult->response->errorMessage) throw new \Exception($apiResult->response->errorMessage);
        $this->apiResult = $apiResult;

    }

    public function getData() : Collection
    {
        return $this->data;
    }
    public function parseData() : Collection
    {
        $data = [];
        collect($this->apiResult->response->data->data)
            ->transform(function ($items, $numkey) use (&$data) {
                //return $item->{$entity};
                foreach ($items as $UpperLevelKey => $item_Arr) {
                    foreach($item_Arr as $itemkey=>$val) {
                       $data[$numkey][$UpperLevelKey . '_' . $itemkey] = $val;
                    }
                }

            });
        $this->data = collect($data);
        return $this->data;
    }
    public function getCountPages() : int
    {
        return $this->pageCount;

    }
    public function parseCountPages() : int
    {
        $this->pageCount = $this->apiResult->response->data->pageCount;
        return $this->pageCount;

    }
    public function getCount() : int
    {
        return $this->count;

    }
    public function parseCount()
    {

    }



}
