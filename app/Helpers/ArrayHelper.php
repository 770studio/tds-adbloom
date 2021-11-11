<?php

namespace App\Helpers;

use Illuminate\Support\Collection;

class ArrayHelper
{
    public static function stackNotEmpty(array $array): array
    {
        foreach ($array as $key => $value) {

            $empty = $value instanceof Collection
                ? $value->isEmpty()
                : !(bool)$value;

            if ($empty) {
                unset($array[$key]);
            }
        }

        return $array;
    }


}
