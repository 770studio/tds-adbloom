<?php

namespace App\Models;




use Illuminate\Contracts\Support\Arrayable;
use ReflectionClass;

class RedirectStatus implements Arrayable //extends Model
{

    const success = 'approved';
    const oq = 'oq';
    const dq = 'dq';
    const reject = 'rejected';


    /*    public function getRouteKeyName()
        {
            return 'code';
        }*/


    public static function all()
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }

    public static function exists($status) : bool
    {
        return in_array($status, self::all());
    }

    public static function fromStr($redirect_status_str)
    {
        $redirect_status_str = strtolower($redirect_status_str);
        return self::exists($redirect_status_str)
            ? $redirect_status_str
            : false;

    }

    public function toArray()
    {
        return self::all();
    }

    public static function indexes()
    {
        return array_keys(
            self::all()
        );
    }


}
