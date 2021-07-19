<?php

namespace App\Models;




use ReflectionClass;

class RedirectStatus //extends Model
{

    const success = 'success';
    const oq = 'oq';
    const dq = 'dq';
    const reject = 'reject';


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
}
