<?php


namespace App\Helpers;


use App\Models\Widget;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

final class RedirectHelper
{
    //TODO refactor to TRAIT
    public static function opportunity($status): RedirectResponse
    {
        $url = config('app.widget_url') . "/status/?status={$status}";
        Log::channel('queue')->debug('eventually redirected to:' . $url);
        return redirect()->away($url);
    }


    /**
     * Based on the widgetId provided find Widget specific redirect settings configured in their profile. Forward
     * params to the URL (in addition to status): widgetId=&partnerId=&clickId=
     * The final URL might look like this:
     * https://dev.tds.adbloom.co/redirect/o8BVO8WCBjfywg3RYAqVH/reject/?split=true&clickid
     * =1021c31dfc7e0d6cfc1df1619c50601ag0_itHR0O-oY9lR9dEL
     */
    public static function widget(Widget $widget, $click_id, $status): RedirectResponse
    {
        $append = sprintf("status=%s&widgetId=%s&partnerId=%s&clickId=%s",
            $status, $widget->short_id, $widget->Partner->external_id, $click_id);

        $url = $widget->redirect_url
            ? UrlHelper::appendTo($widget->redirect_url, $append, true)
            : config('app.widget_url') . '/status/?' . $append;

        Log::channel('queue')->debug('eventually redirected to:' . $url);

        return redirect()->away($url);
    }
}
