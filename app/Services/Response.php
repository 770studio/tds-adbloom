<?php


namespace App\Services;


use App\Interfaces\ResponseIF;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Response implements ResponseIF
{

    protected $pageCount;
    protected $data;
    protected $count;
    protected $apiResult;
    protected Model $relModel;

    /**
     * @throws Exception
     */
    public function __construct(Model $relModel)
    {
        $this->relModel = $relModel;
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
        return (int)$this->apiResult->response->count;

    }

    public function parseCount()
    {

    }

    public function validate()
    {

    }


}
