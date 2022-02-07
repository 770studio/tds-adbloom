<?php

namespace App\Models\Infrastructure;

use Illuminate\Contracts\Support\Arrayable;

abstract class ArrayDataStruct implements Arrayable
{
    public static array $BASE_ARRAY = [];

    public static function getKey($value)
    {
        return static::getShortName($value);
    }

    /**
     * @param mixed $longName
     * @return false|int|string
     */
    public static function getShortName($longName)
    {
        return array_search($longName, static::$BASE_ARRAY, true);
    }

    public static function getValue($key)
    {
        return static::getLongName($key);
    }

    /**
     * @param int|string $shortName
     * @return mixed
     */
    public static function getLongName($shortName)
    {
        return static::$BASE_ARRAY[$shortName] ?? null;
    }

    public static function getList(): array
    {
        $keys = array_keys(static::$BASE_ARRAY);
        return array_combine($keys, $keys);
    }

    public function toArray(): array
    {
        return static::$BASE_ARRAY;
    }
}
