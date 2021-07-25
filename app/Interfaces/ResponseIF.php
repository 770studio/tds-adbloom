<?php


namespace App\Interfaces;


use Illuminate\Support\Collection;

interface ResponseIF
{
    function validate();

    function parseData(): Collection;

}
