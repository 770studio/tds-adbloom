<?php


namespace App\Helpers;


use Illuminate\Http\RedirectResponse;

final class RedirectHelper
{
    public const OPPORTUNITY_REDIRECT_DOMAIN = 'https://widget.adbloom.co';

    public static function opportunity($status): RedirectResponse
    {
        return redirect()->away(self::OPPORTUNITY_REDIRECT_DOMAIN . "/status/?status={$status}");
    }

}
