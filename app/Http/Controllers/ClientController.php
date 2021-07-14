<?php

namespace App\Http\Controllers;

use App\Helpers\RedirectHelper;
use App\Jobs\doPostBackJob;
use App\Models\Client;
use App\Models\RedirectStatus;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    const DEFAULT_REDIRECT_DOMAIN = 'https://widget.adbloom.co';

    public function trackOpportunity(Request $request, Client $client, string $surveyID, RedirectStatus $redirect_status)
    {

        // dd( $request, $client, $surveyID, $redirect_status);

        //if (!$request->clickid) abort(404, 'clickid is required');

        if ($request->clickid) {
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
                    // abort(404, 'undefined redirect status');


            }

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

        return RedirectHelper::opportunity(
            $client->redirect_to_domain ?? self::DEFAULT_REDIRECT_DOMAIN
            , $redirect_status->code
        );

    }
}

