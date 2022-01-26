<?php


namespace App\Interfaces;


use Illuminate\Support\Collection;

interface ResponseIF
{
    function validate(): self;

    function parseData(): Collection;


}
