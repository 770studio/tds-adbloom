<?php

namespace App\Services\SchlesingerAPI;

use Illuminate\Support\Collection;

class SchlesingerSurveyQualificationsListResponse extends SchlesingerV2Response
{


    public function parseData(): Collection
    {
        return collect(data_get($this->apiResult, "SurveyQualifications"));


    }

}
