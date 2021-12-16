<?php

namespace App\Services\GeneralResearchAPI;

use App\Exceptions\BreakingException;
use App\Models\Widget;
use App\Traits\Widgetable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeneralResearchAPIStatus
{
    use Widgetable;

    public function check(string $trans_id)
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

        if (!$resp_object) {
            throw new BreakingException('external api can not be reached, 500 or smth...');
        }
        if (!isset($resp_object->status)) {
            throw new BreakingException('external api status can not be read');
        }


        if ($resp_object->widgetId) {
            $this->setWidget(Widget::findByShortId($resp_object->widgetId)->firstOr(function () use ($resp_object) {
                Log::channel('queue')->debug('widget not found:' . $resp_object->widgetId);
                throw new BreakingException('widget not found');
            }));
        }

        Log::channel('queue')->debug('grl status reply:' . json_encode($resp_object));


    }

}
