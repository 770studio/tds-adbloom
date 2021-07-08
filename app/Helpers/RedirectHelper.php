<?php


namespace App\Helpers;


class RedirectHelper
{

    public static function opportunity($url, $status)
    {

        return redirect()->away(
            (is_null(parse_url($url, PHP_URL_HOST))
                ? '//' :
                '')
            . $url . "/status/?status={$status}");
    }

}
