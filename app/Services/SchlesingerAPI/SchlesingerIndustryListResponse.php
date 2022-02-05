<?php

namespace App\Services\SchlesingerAPI;

use Illuminate\Support\Collection;

class SchlesingerIndustryListResponse extends SchlesingerV1Response
{


    public function parseData(): Collection
    {
        return collect($this->apiResult->industries)
            ->transform(function (object $industry) {
                return (array)$industry;
            });

    }

}
