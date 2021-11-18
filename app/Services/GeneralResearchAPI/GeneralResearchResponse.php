<?php


namespace App\Services\GeneralResearchAPI;


use App\Exceptions\BreakingException;
use App\Models\Opportunity;
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
            throw new BreakingException("external api is not parsable");
        }
        return $this;

    }

    public function transformPayouts(Partner $partner, bool $adbloom30 = false): self
    {
        $denom = $adbloom30
            ? 0.7 / 100    //30% took adbloom
            : 1 / 100;

        $this->transformBuckets(function (&$item) use ($partner, $denom) {
            $item->payout->max = number_format($partner->calulateReward($item->payout->max) * $denom, 2,
                '.', '');
            $item->payout->min = number_format($partner->calulateReward($item->payout->min) * $denom, 2,
                '.', '');
            return $item;
        });

        return $this;
    }

    public function transformDuration(): self
    {
        $this->transformBuckets(function (&$item) {
            // округлить до ближайшего целого (это же минуты)
            $item->duration->max = number_format($item->duration->max / 60, 0,
                '.', '');
            $item->duration->min = number_format($item->duration->min / 60, 0,
                '.', '');
            return $item;
        });

        return $this;
    }

// TODO refactor transform... to a factory
    public function hideUri(): self
    {
        $this->transformBuckets(function (&$item) {
            unset($item->uri);
            return $item;
        });

        return $this;
    }

    /**
     *
     * for temporary use!
     */
    public function getBucket(): array
    {
        try {
            $offerwall = $this->parseData()->get('offerwall');
            $bucket = $offerwall->buckets[0];

            return
                [
                    'short_id' => $offerwall->id, // id
                    'name' => 'Paid Surveys',   // title
                    'image' => 'https://dev.tds.adbloom.co/storage/assets/creatives/e23bae6e2e269b78738005ef8c9c8914105f4321.png',
                    'description' => 'Get paid for your opinion today! Surveys take a few minutes and you\'ll earn each time you complete one.',
                    'link' => $bucket->uri, // url
                    'payout' => $bucket->payout->max, // reward
                    'call_to_action' => 'Start Now', // callToAction
                    'type' => Opportunity::TYPES['survey'], // for timeToComplete to show up
                    'timeToComplete' => $bucket->duration->max,

                ];
        } catch (Exception $e) {
            //Must not add an Opportunity if GRL API doesn't return any options.
            return [];
        }


    }

    public function parseData(): Collection
    {
        return collect($this->apiResult);
    }

    private function transformBuckets(callable $callback): void
    {
        $this->setData(
            collect($this->apiResult)
                ->transform(function ($item, $key) use ($callback) {
                    if ($key !== 'offerwall') {
                        return $item;
                    }
                    //dump($item);
                    $item->buckets = collect($item->buckets)->transform(function ($item, $key) use ($callback) {
                        return $callback($item);
                    });
                    return $item;
                })
        );
    }

}
