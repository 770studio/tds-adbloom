<?php


namespace App\Services\YoursurveysReadmeIoAPI;


use App\Exceptions\BreakingException;
use App\Services\Response;
use Exception;
use Illuminate\Support\Collection;

class YourSurveysResponse extends Response
{

    /**
     * @throws Exception
     */
    public function validate(): self
    {
        if ($this->apiResult->status != 'success') throw new BreakingException("YoursurveysReadmeIoAPI returned an error:" . ($this->apiResult->messages ?? ''));
        return $this;

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
