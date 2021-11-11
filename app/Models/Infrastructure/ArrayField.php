<?php


namespace App\Models\Infrastructure;


use Illuminate\Contracts\Support\Arrayable;
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

    public static function equalsToAll($array): bool
    {
        // dd(array_diff(static::all(), (array)$array));
        return !array_diff(static::all(), (array)$array);
    }

    public static function exists($key): bool
    {
        return in_array($key, static::indexes());
    }

    public static function keyExists($key): bool
    {
        return in_array($key, static::all());
    }

    public static function indexes()
    {
        return array_keys(
            static::all()
        );
    }

    public static function all()
    {
        $oClass = new ReflectionClass(static::class);
        return $oClass->getConstants();
    }

    public static function getName($key)
    {
        return static::exists($key)
            ? static::all()[$key]
            : null;
    }

    public static function collection()
    {
        return collect(static::all_flipped());
    }

    public static function all_flipped()
    {
        return array_flip(
            static::all()
        );
    }

    public static function values()
    {
        return array_values(
            static::all()
        );
    }

    public function toArray()
    {
        return static::all();
    }
}
