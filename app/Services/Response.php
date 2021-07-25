<?php


namespace App\Services;


use App\Interfaces\ResponseIF;
use Exception;
use Illuminate\Support\Collection;
use stdClass;

class Response implements ResponseIF
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
        $this->apiResult = $apiResult;
        $this->validate();
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

    public function validate()
    {

    }


}
