<?php


namespace App\Models\Infrastructure;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use ReflectionClass;

abstract class ArrayField implements Arrayable
{

    public static function fromStr($value)
    {
        $value = strtolower($value);
        return static::exists($value)
            ? $value
            : false;

    }

    /**
     *  if we have full house
     *  e.g. all platforms : [1,2,3] == [1,2,3]
     */
    public static function equalsToAll($array): bool
    {
        // dd(array_diff(static::all(), (array)$array));
        return !array_diff(static::all(), (array)$array);
    }

    /**
     *  key is a string key like "mobile" for platform
     *  indexes are platform string keys: mobile, desktop, etc...
     */
    public static function exists($key): bool
    {
        return in_array($key, static::indexes());
    }

    /**
     *  key is an id key like 1,2 or 3 for platform
     *  all are platform id keys: 1,2 or 3 etc...
     */
    public static function keyExists($key): bool
    {
        return in_array($key, static::all());
    }

    /**
     *  indexes are platform string keys: mobile, desktop, etc...
     */
    public static function indexes(): array
    {
        return array_keys(
            static::all()
        );
    }

    /**
     *  all array : 1=>mobile, 2=>desktop, etc...
     */
    public static function all(): array
    {
        $oClass = new ReflectionClass(static::class);
        return $oClass->getConstants();
    }

    /**
     *  if string key exists ('desktop') return its id
     *
     */
    public static function getName($key)
    {
        return static::exists($key)
            ? static::all()[$key]
            : null;
    }

    /**
     *  a collection formatted as:
     *   1-> desktop,
     *   2-> mobile,
     *   etc..
     */
    public static function collection(): Collection
    {
        return collect(static::all_flipped());
    }

    /**
     *  an array formatted as:
     *   1-> desktop,
     *   2-> mobile,
     *   etc..
     */
    public static function all_flipped(): array
    {
        return array_flip(
            static::all()
        );
    }

    /**
     *  return an array like [1,2,3] e.g. for platform
     */
    public static function values(): array
    {
        return array_values(
            static::all()
        );
    }

    public function toArray(): array
    {
        return static::all();
    }
}
