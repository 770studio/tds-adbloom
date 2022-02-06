<?php

namespace App\Services\SchlesingerAPI;

use Illuminate\Support\Collection;

class SchlesingerQualificationsListResponse extends SchlesingerV1Response
{
    public function parseData(): Collection
    {
        return collect(data_get($this->apiResult, "qualifications"));
    }

}
