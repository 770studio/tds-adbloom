<?php


namespace App\Interfaces;


use App\Services\DaliaPublisherAPI\DaliaPublisherAPIServiceResponse;
use Carbon\Carbon;

interface DaliaPublisherAPIServiceIF
{
    public function getAll(): DaliaPublisherAPIServiceResponse;

    public function deleteInExistent(Carbon $updateTime);

}
