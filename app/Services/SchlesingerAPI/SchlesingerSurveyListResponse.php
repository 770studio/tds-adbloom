<?php

namespace App\Services\SchlesingerAPI;

use Illuminate\Support\Collection;

class SchlesingerSurveyListResponse extends SchlesingerV2Response
{


    public function parseData(): Collection
    {
        return collect($this->apiResult->Surveys)
            ->transform(function (object $survey) {
                return (array)$survey;
            });

    }

}
