<?php


namespace App\Helpers;


use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

final class RedirectHelper
{

    public static function opportunity($status): RedirectResponse
    {
        $url = config('app.widget_url') . "/status/?status={$status}";
        Log::channel('queue')->debug('eventually redirected to:' . $url);
        return redirect()->away($url);
    }

}
