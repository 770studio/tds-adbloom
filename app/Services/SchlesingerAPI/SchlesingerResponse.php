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
        return $this;

    }

    public function parseData(): Collection
    {
        return
            collect($this->apiResult->Surveys)
                ->transform(function ($survey, $key) {
                    return $survey;
                });

    }
}
