<?php

namespace App\Helpers;

class Stack
{
    private array $array;

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    public function useOne()
    {
        shuffle($this->array);
        return array_shift($this->array);
    }

}
