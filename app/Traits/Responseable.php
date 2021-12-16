<?php

namespace App\Traits;

use App\Interfaces\ResponseIF;


trait Responseable
{
    private ResponseIF $responseProcessor;

    public function getResponseProcessor(): ResponseIF
    {
        return $this->responseProcessor;
    }
}
