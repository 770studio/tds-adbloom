<?php


namespace App\Services;


use App\Interfaces\ResponseIF;
use Illuminate\Support\Collection;

class Response implements ResponseIF
{

    protected $pageCount;
    protected $data;
    protected $count;
    protected object $apiResult;


    public function __construct($apiResult)
    {
        $this->setData($apiResult);
    }

    public function setData(object $apiResult): self
    {
        $this->apiResult = $apiResult;
        return $this;
    }

    public function getData(): Collection
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return method_exists($this->apiResult, 'toArray')
            ? $this->apiResult->toArray()
            : (array)$this->apiResult;
    }

    public function parseData(): Collection
    {
        //implemented on upper level
        return collect([]);
    }

    public function getCountPages(): int
    {
        return $this->pageCount;

    }

    public function parseCountPages(): int
    {
        $this->pageCount = $this->apiResult->response->data->pageCount;
        return (int)$this->pageCount;

    }

    public function getCount(): int
    {
        return (int)$this->apiResult->response->data->count;

    }

    public function parseCount()
    {

    }

    public function validate(): self
    {
        return $this;
    }


}
