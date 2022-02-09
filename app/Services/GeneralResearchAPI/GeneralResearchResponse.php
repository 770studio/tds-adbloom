<?php


namespace App\Services\GeneralResearchAPI;


use App\Exceptions\BreakingException;
use App\Helpers\Stack;
use App\Helpers\StoreImageHelper;
use App\Models\Opportunity;
use App\Models\Partner;
use App\Services\Response;
use Exception;
use Illuminate\Support\Collection;

class GeneralResearchResponse extends Response
{

    public const RANDOM_TITLES = [
        'Daily surveys', 'Fresh surveys', 'New survey now', 'TOP surveys', 'Paid survey'
    ];
    public const RANDOM_DESCS = [
        'Find out if you qualify for this paid survey by answering a few brief questions',
        'Get Paid for your opinion today. Simply answer a few questions to qualify for this paid survey',
        'SYW Max surveys are a great way to earn SYW max points',
        'Random survey is chosen just for you!',
        'Your opinion is needed! Share it and earn SYW rewards',
    ];

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
    public function transformUri(): self
    {
        $this->transformBuckets(function (&$item) {
            if (preg_match("/\/api\/v1\/(.*)$/", $item->uri, $match)) {
                $item->uri = route('grlgo', ['path' => $match[1]]);
            }
            return $item;
        });

        return $this;
    }

    public function hideUri(): self
    {
        $this->transformBuckets(function (&$item) {
            unset($item->uri);
            return $item;
        });

        return $this;
    }


    public function getBuckets(int $limit = 5): Collection
    {
        try {

            $offerwall = $this->parseData()->get('offerwall');
            /** @var  $offerwall_buckets Collection */
            $offerwall_buckets = $offerwall->buckets;
            $buckets = collect();

            $titles = new Stack(self::RANDOM_TITLES);
            $descs = new Stack(self::RANDOM_DESCS);
            $creatives = new Stack (StoreImageHelper::getGrlCreativeUrls());

            foreach ($offerwall_buckets->slice(0, $limit) as $key => $offerwall_bucket) {
                $buckets->push(
                    new Opportunity([
                        'short_id' => $offerwall->id, // id
                        'name' => $titles->useOne(),   // title
                        'image' => $creatives->useOne(),
                        'description' => $descs->useOne(),
                        'link' => $offerwall_bucket->uri, // url
                        'payout' => $offerwall_bucket->payout->max, // reward
                        'call_to_action' => 'Start Now', // callToAction
                        'type' => Opportunity::TYPES['survey'], // for timeToComplete to show up
                        'timeToComplete' => $offerwall_bucket->duration->max,
                        'incentive' => true,
                        'mixin' => true,

                    ])
                );
            }

            return $buckets;

        } catch (Exception $e) {
            //Must not add an Opportunity if GRL API doesn't return any options.
            return collect();
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
