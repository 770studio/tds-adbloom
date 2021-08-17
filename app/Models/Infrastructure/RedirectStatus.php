<?php

namespace App\Models\Infrastructure;




class RedirectStatus extends ArrayField
{

    const success = 'approved';
    const oq = 'oq';
    const dq = 'dq';
    const reject = 'rejected';


    /*    public function getRouteKeyName()
        {
            return 'code';
        }*/




    public static function fakeSendPendingStatus() : array
    {
        return array_map(
            function($val){
                return true;
            },
            self::all());
    }


}
