<?php


namespace App\Services\GeneralResearchAPI;


use App\Exceptions\BreakingException;
use App\Jobs\doPostBackJob;
use App\Models\Infrastructure\RedirectStatus_Client;
use App\Traits\Responseable;
use App\Traits\Widgetable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeneralResearchAPIService
{
    use Widgetable, Responseable;

    public array $params = [];
    private string $api_url;
    private int $timeout;
    private Request $request;
    private GeneralResearchAPIStatus $status;

    public function __construct(Request                  $request,
                                GeneralResearchResponse  $responseProcessor,
                                GeneralResearchAPIStatus $status,
                                                         $n_bins = 5)
    {
        $this->request = $request;
        $this->status = $status;
        $this->responseProcessor = $responseProcessor;

        $this->api_url = sprintf("%s/%s/offerwall/45b7228a7/",
            config('services.generalresearch.api_base_url'),
            config('services.generalresearch.api_key')
        );

        $this->timeout = config('services.common_api.timeout');

        $this->params = [
            'bpuid' => $request->userId ?? 'generic',
            'format' => 'json',
            'ip' => $request->ip(), // '69.253.144.82'
            'country_iso' => $request->country,
            'min_payout' => 1,
            'n_bins' => $n_bins,

            /*age
            zip
            gender*/
        ];


    }

    public function checkStatus($trans_id): GeneralResearchAPIStatus
    {
        $this->status->check($trans_id);
        return $this->getStatus();
    }

    public function getStatus(): GeneralResearchAPIStatus
    {
        return $this->status;
    }

    /**
     * @throws Exception
     */
    public function makeRequest(): object
    {
        $params = http_build_query(
            array_merge($this->params, [
                'clickId' => $this->getClickID(),
                'widgetId' => $this->widget->short_id
            ])
        );

        $url = $this->api_url . '?' . $params;

        //TODO for tests returns 5 buckets
        if (app()->isLocal()) {
            $url = "https://fsb.generalresearch.com/6c7c06f784d14fb98a292cf1410169b1/offerwall/45b7228a7/?bpuid=akjhasdhhj&format=json&ip=69.253.144.82&min_payout=1&n_bins=5&clickId=102374d48cb2af5b8059ca737aa568&widgetId=Vhf3stqbo8WNDBiBoZmVF";
        }

        Log::channel('queue')->debug('grl api request:' . $url);

        $resp_object = Http::timeout($this->timeout)
            ->get($url)
            ->object();
        if (!$resp_object) {
            throw new BreakingException('external api can not be reached, 500 or smth...');
        }

        return $resp_object;

    }

    /*
     * Before the GRL Proxy API call we need to get a Click ID from Tune.
     * To do so call this endpoint https://trk.adbloom.co/aff_c?&aff_id={partnerId}&offer_id=389&aff_click_id={clickId}&aff_sub5={userId}&aff_unique2={birthdate}&aff_unique3={email}&aff_sub2={country}&aff_sub4={gender}&source=widget&format=json. Replace partnerId with correct ID the request was made with.
     * Example request https://trk.adbloom.co/aff_c?&aff_id=2&offer_id=389&format=json (without extra params).
     */
    private function getClickID(): string
    {

        $data = [
            'aff_click_id' => $this->request->get('clickId'),
            'aff_unique1' => $this->request->get('userId'),
            'aff_unique2' => $this->request->get('birthdate'),
            'aff_unique3' => $this->request->get('email'),
            'aff_sub2' => $this->request->get('country'),
            'aff_sub4' => $this->request->get('gender'),
            'aff_sub5' => $this->widget->short_id,
            'source' => 'widget',
            'format' => 'json',
            'offer_id' => '389',
            'aff_id' => $this->partner->external_id,
        ];


        return Http::timeout($this->timeout)
            ->get("https://trk.adbloom.co/aff_c?" . http_build_query($data))
            ->object()
            ->response->data->transaction_id; // выкинет иксепшн и дальше не пойдем


    }


    /**
     * @throws Exception
     *
     */
    public function sendStatusToTune(GeneralResearchAPIStatus $status_object): string
    {

        $back_url = 'none';
        /*
         *   If status=3 the survey is successful, send a conversion to Tune
         *   If status=2 the survey is rejected, send a conversion to Tune (goal_id=389)
         */
        Log::channel('queue')->debug('status:' . $status_object->getStatus());

        switch ($status_object->getStatus()) {
            case RedirectStatus_Client::success :
                $back_url = sprintf("https://trk.adbloom.co/aff_lsr?transaction_id=%s&amount=%s&adv_sub=%s",
                    $status_object->getClickID(),
                    number_format($status_object->getPayout() / 100, 2, '.', ''), // in dollars
                    $status_object->getTransId()
                );
                doPostBackJob::dispatch($back_url)->onQueue('send_to_tune');
                return RedirectStatus_Client::success;
                break;
            case RedirectStatus_Client::reject :
                $back_url = sprintf("https://trk.adbloom.co/aff_goal?a=lsr&goal_id=%d&transaction_id=%s&adv_sub=%s",
                    153,
                    $status_object->getClickID(),
                    $status_object->getTransId()
                );
                doPostBackJob::dispatch($back_url)->onQueue('send_to_tune');

                break;

            default:
                throw new BreakingException('sendStatusToTune: wrong status:' . $status_object->getStatus());
        }

        return RedirectStatus_Client::reject;

    }


}
