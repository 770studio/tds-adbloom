<?php

namespace App\Helpers;

use App\Models\Infrastructure\AlertDTO;

class CompareObjectValueHelper
{

    private int $minThreashold;
    private string $prop;
    private float $diff;
    private string $direction;
    private object $object2;
    private object $object1;

    public function __construct(string $prop_name, int $min_threashold = 0)
    {
        $this->minThreashold = $min_threashold;
        $this->prop = $prop_name;
    }

    /**
     * consider $object1 a recent period , $object2 - an older period
     * 1. if both are zero then no change obviously
     * 2. $object2 > $object1 - negative change
     */
    public function compare(object $object1, object $object2): float
    {
        $val1 = $object1->{$this->prop};
        $val2 = $object2->{$this->prop};
        // both are zero
        if (!$val1 && !$val2) return 0; // no diff

        if ($val2 > $val1) {
            $diff = 100 * ($val2 - $val1) / $val2;
            if ($diff >= $this->minThreashold) {
                return round($diff, 2);
            }
        }

        return 0;
    }

    public function compareBothWays(object $object1, object $object2): self
    {
        $this->object1 = $object1;
        $this->object2 = $object2;

        if ($this->diff = $this->compare($object1, $object2)) {
            $this->direction = 'DOWN';
        } elseif ($this->diff = $this->compare($object2, $object1)) {
            $this->direction = 'UP';
        }

        return $this;

    }

    public function hasDiff(): bool
    {
        return $this->diff > 0;
    }

    public function toAlert(): AlertDTO
    {
        return AlertDTO::fromArray([
            'direction' => $this->direction,
            'diff_prs' => $this->diff,
            'recent_item_prs_value' => $this->object1->{$this->prop},
            'older_item_prs_value' => $this->object2->{$this->prop},
            'offer_name' => $this->object2->Offer_name
        ]);
    }


}
