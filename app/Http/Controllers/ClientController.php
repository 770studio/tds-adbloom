<?php

namespace App\Http\Controllers;

use App\Helpers\RedirectHelper;
use App\Jobs\doPostBackJob;
use App\Models\Client;
use App\Models\Infrastructure\RedirectStatus_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{

    public function trackOpportunity(Request $request, Client $client, string $redirect_status_str)
    {
        //  dd($client, $redirect_status_str,  );

        if (!$redirect_status = RedirectStatus_Client::fromStr($redirect_status_str)) {
            Log::channel('queue')->error('unexpected incoming status:' . $redirect_status_str, ['ip' => $request->getClientIp()]);
        } else {
            Log::channel('queue')->debug('incoming status:' . $redirect_status_str);
        }

        if ($request->clickid) {
            switch ($redirect_status) {
                case RedirectStatus_Client::reject:
                case RedirectStatus_Client::oq:
                case RedirectStatus_Client::dq:
                    Log::channel('queue')->debug('sent to queue, doPostBackJob: status:' . $redirect_status);
                    doPostBackJob::dispatch(
                        "https://trk.adbloom.co/aff_goal?a=lsr&goal_name={$redirect_status}&transaction_id={$request->clickid}"
                    )->onQueue('postback_queue');
                    break;
                case RedirectStatus_Client::success:
                    doPostBackJob::dispatch(
                        "https://trk.adbloom.co/aff_lsr?transaction_id={$request->clickid}"
                    )->onQueue('postback_queue');
                    Log::channel('queue')->debug('sent to queue, doPostBackJob: status:' . $redirect_status);
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

        return RedirectHelper::opportunity($redirect_status_str);

    }
}

