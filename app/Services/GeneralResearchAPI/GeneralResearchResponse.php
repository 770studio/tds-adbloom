<?php


namespace App\Services\GeneralResearchAPI;


use App\Models\Partner;
use App\Services\Response;
use Exception;
use Illuminate\Support\Collection;

class GeneralResearchResponse extends Response
{

    /**
     * @throws Exception
     */
    public function validate(): self
    {
        if ($this->apiResult->info->success != 'true') {
            throw new Exception("GeneralResearchResponse is not parsable");
        }
        return $this;

    }

    public function parseData(): Collection
    {
        return collect($this->apiResult);
    }

    public function transformResponse(Partner $partner): Collection
    {
        return $this->parseData()
            ->transform(function ($item, $key) use ($partner) {
                if ($key != 'offerwall') return $item;

                $item->buckets = collect($item->buckets)->transform(function ($item, $key) use ($partner) {
                    $item->payout->max = $partner->calulateReward($item->payout->max);
                    $item->payout->min = $partner->calulateReward($item->payout->min);
                    return $item;
                });
                return $item;
            });
    }
}
