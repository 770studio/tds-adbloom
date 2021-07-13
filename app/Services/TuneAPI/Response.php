<?php


namespace App\Services\TuneAPI;


use Exception;
use Illuminate\Support\Collection;
use stdClass;

class Response
{
    protected $pageCount;
    protected $data;
    protected $count;
    protected $apiResult;

    /**
     * @throws Exception
     */
    public function __construct(stdClass $apiResult)
    {
        if ($apiResult->response->errorMessage) throw new Exception($apiResult->response->errorMessage);
        $this->apiResult = $apiResult;

    }

    public function getData() : Collection
    {
        return $this->data;
    }

    public function parseData() : Collection
    {
        //implemented on upper level
        return collect([]);
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
