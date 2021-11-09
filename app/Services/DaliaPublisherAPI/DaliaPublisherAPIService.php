<?php


namespace App\Services\DaliaPublisherAPI;


use App\Interfaces\DaliaPublisherAPIServiceIF;
use App\Models\Integrations\DaliaOffers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class DaliaPublisherAPIService implements DaliaPublisherAPIServiceIF
{
    private string $publisher_user_uuid;
    private DaliaPublisherAPIServiceResponse $responseProcessor;


    public function __construct(string $publisher_user_uuid, DaliaPublisherAPIServiceResponse $responseProcessor)
    {
        $this->publisher_user_uuid = $publisher_user_uuid;
        $this->responseProcessor = $responseProcessor;
    }

    public function getAll(): DaliaPublisherAPIServiceResponse
    {

        $url = sprintf("https://platform.opinionsample.com/api/publisher/v1/publisher_users/%s/publisher_offers/",
            $this->publisher_user_uuid
        );

        $response = Http::withOptions(
            ['debug' => true]
        )->get($url);

       // dd($response->object());
        return $this->responseProcessor->setData(
            $response->object()
        );


    }

    public function deleteInExistent(Carbon $updateTime)
    {
        $updateTime = $updateTime->toDateTimeString();
        DaliaOffers::where('updated_at', '<' , $updateTime)
            ->where('created_at', '<' , $updateTime)
            ->delete();
    }

}
