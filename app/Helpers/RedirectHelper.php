<?php


namespace App\Helpers;


class RedirectHelper
{

    public static function opportunity($domain, $status)
    {
        return redirect()->away($domain . "/status/?status={$status}");
    }

}
