<?php

namespace App\Http\Controllers;

use App\Jobs\doPostBackJob;
use App\Models\Client;
use App\Models\RedirectStatus;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    const DEFAULT_REDIRECT_DOMAIN = 'https://widget.adbloom.co/';

    public function trackOpportunity(Request $request, Client $client, RedirectStatus $redirect_status)
    {

        //dd( $request, $client, $redirect_status);

        if (!$request->clickid) return response('', 400);

        switch ($redirect_status->code) {
            case "reject":
            case "oq":
            case "dq":
                doPostBackJob::dispatch(
                    "https://trk.adbloom.co/aff_goal?a=lsr&goal_name={$redirect_status->code}&transaction_id={$request->clickid}"
                );
                break;
            case "success":
                doPostBackJob::dispatch(
                    "https://trk.adbloom.co/aff_lsr?transaction_id={$request->clickid}"
                );
                break;

            default:
                return response('', 400);

        }

        /*      Вот постбэк для статуса success:
                https://trk.adbloom.co/aff_lsr?transaction_id={clickID}
                Вот URL для постбэка в случае перехода пользователя на страницы со статусами reject, oq, dq:
                https://trk.adbloom.co/aff_goal?a=lsr&goal_name={status}&transaction_id={clickID}

                URL на который нужно перенаправить пользователя должен указываться в профиле Client. B он имеет вид:
                {domain}/status/?status={status}
                Где:
                {domain} любой URL, либо по умолчению https://widget.adbloom.co/
                {status} значение status (да, три слова статуса, криво, потом на фронте подправим).
                3:48
                Например https://widget.adbloom.co/status/?status=success
                */

        $domain = $client->redirect_to_domain ?? self::DEFAULT_REDIRECT_DOMAIN;
        return redirect($domain . "/status/?status={$redirect_status->code}");
    }
}

