<?php

namespace App\Services\GeneralResearchAPI;

use App\Exceptions\BreakingException;
use App\Models\Infrastructure\RedirectStatus_Client;
use App\Models\Widget;
use App\Traits\Widgetable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeneralResearchAPIStatus
{
    use Widgetable;

    private object $response;

    public function __construct()
    {
        $this->setDefaults();
    }

    private function setDefaults(): void
    {
        $this->response = (object)[
            'tsid' => null,
            'payout' => 0,
            'status' => null,
        ];
    }

    public function check(string $trans_id): void
    {

        $url = sprintf("%s/%s/status/%s/",
            config('services.generalresearch.api_base_url'),
            config('services.generalresearch.api_key'),
            $trans_id
        );

        Log::channel('queue')->debug('grl status request:' . $url);


        $resp_object = Http::timeout(config('services.common_api.timeout'))
            ->get($url)
            ->object();


        if (!$resp_object || !isset($resp_object->status)) {
            $this->setDefaults();
            return;
        }


        if (isset($resp_object->kwargs->widgetId)) {
            $this->setWidget(Widget::findByShortId($resp_object->kwargs->widgetId)->firstOr(function () use ($resp_object) {
                Log::channel('queue')->debug('widget not found:' . $resp_object->widgetId);
                //throw new BreakingException('widget not found');
            }));
        }

        Log::channel('queue')->debug('grl status reply:' . json_encode($resp_object));

        Log::channel('queue')->debug('status:' . $resp_object->status);

        $this->set($resp_object);
    }

    public function getTransId()
    {
        return $this->response->tsid;
    }

    public function getPayout(): float
    {
        return (float)$this->response->payout;
    }

    public function getStatus(): string
    {
        switch ($this->response->status) {
            case 3:
                return RedirectStatus_Client::success;
        }
        return RedirectStatus_Client::reject;
    }

    public function getClickID()
    {
        return $this->response->kwargs->clickId ?? null;
        //throw new BreakingException('external api data (clickId) can not be read');

    }

    private function set(object $resp_object): void
    {
        $this->response = $resp_object;
    }


}
