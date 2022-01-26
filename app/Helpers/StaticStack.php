<?php

namespace App\Helpers;

class StaticStack
{
    public static array $array;

    public function __construct(array $array)
    {
        self::$array = $array;
    }

    public function useOne()
    {
        shuffle(self::$array);
        return array_shift(self::$array);
    }

}
