<?php

namespace App\Services\SchlesingerAPI;

use App\Exceptions\BreakingException;
use App\Services\Response;

class SchlesingerV1Response extends Response
{


    /**
     * @throws BreakingException
     */
    public function validate(): self
    {

        if ($this->apiResult->result->success !== true) throw new BreakingException("SchlesingerAPI error:" .
            $this->apiResult);

        if ($this->getCount() > 5000) throw new BreakingException("SchlesingerAPI error: total count is way too big");

        return $this;

    }

    public function getCount(): int
    {
        return (int)$this->apiResult->result->totalCount;
    }
}
