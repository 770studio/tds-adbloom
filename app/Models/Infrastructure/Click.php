<?php

namespace App\Models\Infrastructure;

use App\Helpers\RedirectHelper;
use App\Jobs\doPostBackJob;
use App\Models\Widget;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Click
{

    private ?Widget $widget;
    private ?string $clickId;

    public function __construct(?string $click_id, bool $split_click_id = false)
    {
        [$this->clickId, $this->widget] = $this->parseClickID($click_id, $split_click_id);
    }

    private function parseClickID(?string $source_click_id, bool $split_click_id): array
    {

        return $split_click_id
            // first 30  / x
            ? [
                Str::substr($source_click_id, 0, 29),
                Widget::findByShortId(Str::substr($source_click_id, 29))->first()
            ]
            // click_id as is
            : [$source_click_id, null];

    }

    public function getClickId()
    {
        return $this->clickId;
    }

    public function getWidget()
    {
        return $this->widget;
    }

    public function handle(string $request_redirect_status): RedirectResponse
    {

        if (!$system_redirect_status = RedirectStatus_Client::fromStr($request_redirect_status)) {
            Log::channel('queue')->error('unexpected incoming status:' . $request_redirect_status,
                ['ip' => optional(request())->getClientIp()
                ]);
        } else {
            Log::channel('queue')->debug('incoming status:' . $request_redirect_status);
        }

        if (!$this->clickId) {
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

            return RedirectHelper::opportunity($request_redirect_status);
        }


        switch ($system_redirect_status) {
            case RedirectStatus_Client::reject:
            case RedirectStatus_Client::oq:
            case RedirectStatus_Client::dq:
                Log::channel('queue')->debug('sent to queue, doPostBackJob: status:' . $system_redirect_status);
                doPostBackJob::dispatch(
                    "https://trk.adbloom.co/aff_goal?a=lsr&goal_name={$system_redirect_status}&transaction_id={$this->clickId}"
                )->onQueue('postback_queue');
                break;
            case RedirectStatus_Client::success:
                doPostBackJob::dispatch(
                    "https://trk.adbloom.co/aff_lsr?transaction_id={$this->clickId}"
                )->onQueue('postback_queue');
                Log::channel('queue')->debug('sent to queue, doPostBackJob: status:' . $system_redirect_status);
                break;

            default:
                // abort(404, 'undefined redirect status');


        }

        return $this->widget
            ? RedirectHelper::widget($this->widget, $this->clickId, $request_redirect_status)
            : RedirectHelper::opportunity($request_redirect_status);


    }
}
