<?php

namespace App\Helpers;

class CompareObjectValueHelper
{

    private int $minThreashold;
    private string $prop;

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
    public function compare(object $object1, object $object2)
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

}
