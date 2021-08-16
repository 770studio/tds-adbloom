<?php


namespace App\Interfaces;


use App\Services\DaliaPublisherAPI\DaliaPublisherAPIServiceResponse;

interface DaliaPublisherAPIServiceIF
{
    public function getAll(): DaliaPublisherAPIServiceResponse;

}
