<?php


namespace App\Services\GeneralResearchAPI;


use App\Services\Response;
use Exception;
use Illuminate\Support\Collection;

class GeneralResearchResponse extends Response
{

    /**
     * @throws Exception
     */
    public function validate()
    {
        if ($this->apiResult->status != 'success') throw new Exception("YoursurveysReadmeIoAPI returned an error:" . ($this->apiResult->messages ?? ''));

    }

    public function parseData(): Collection
    {
        return
            collect($this->apiResult->surveys)
                ->transform(function ($survey, $numkey) {
                    return [
                        'project_id' => $survey->project_id,
                        'json' => $survey
                    ];

                });

    }

}
