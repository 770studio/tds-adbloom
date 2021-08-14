<?php


namespace App\Services\DaliaPublisherAPI;


use App\Interfaces\DaliaPublisherAPIServiceIF;
use Illuminate\Support\Facades\Http;

class DaliaPublisherAPIService implements DaliaPublisherAPIServiceIF
{
    private string $publisher_user_uuid;

    public function __construct(string $publisher_user_uuid)
    {
        $this->publisher_user_uuid = $publisher_user_uuid;

    }

    public function getAll(): object
    {

        $url = sprintf("https://platform.opinionsample.com/api/publisher/v1/publisher_users/%s/publisher_offers/",
            $this->publisher_user_uuid
        );

        $response = Http::withOptions(
            ['debug' => true]
        )->get($url);

        dd($response->object());

        return $response->object();


    }
}
