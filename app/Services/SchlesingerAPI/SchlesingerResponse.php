<?php

namespace App\Services\SchlesingerAPI;

use App\Exceptions\BreakingException;
use App\Services\Response;
use Illuminate\Support\Collection;

class SchlesingerResponse extends Response
{

    /**
     * @throws BreakingException
     */
    public function validate(): self
    {
        if ($this->apiResult->Result->Success !== true) throw new BreakingException("SchlesingerAPI error:" .
            $this->apiResult);

        if ($this->getCount() > 5000) throw new BreakingException("SchlesingerAPI error: total count is way too big");

        return $this;

    }

    public function parseData(): Collection
    {

        return collect($this->apiResult->Surveys);
        /* ->transform(function ($survey, $key) {
             return $survey;
         });*/

    }

    public function getCount(): int
    {
        return (int)$this->apiResult->TotalCount;

    }
}
