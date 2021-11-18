<?php

namespace App\Helpers;

use ReflectionClass;
use ReflectionProperty;

abstract class DataTransferObject
{

    protected function __construct(array $parameters = [])
    {
        $class = new ReflectionClass(static::class);

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $property = $reflectionProperty->getName();
            if (isset($parameters[$property])) {
                $this->{$property} = $parameters[$property];
            }
        }
    }

}
