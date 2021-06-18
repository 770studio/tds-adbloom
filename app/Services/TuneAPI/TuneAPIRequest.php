<?php


namespace App\Services\TuneAPI;
//TODO rate limiter
// Networks are limited to a maximum of 50 API calls every 10 seconds. If you exceed the rate limit, your API call returns the following error: "API usage exceeded rate limit. Configured: 50/10s window; Your usage: " followed by the number of API calls you've attempted in that 10 second window.

class TuneAPIRequest
{

    const API_TOKEN = "NETzcmyVZWooz2oPYnlWDzOu9kiQmD";
    const API_BASE_URL = "https://adbloom.api.hasoffers.com/Apiv3/json?";


    public function findAll($before): string
    {

        return $this->makeUrl([
            "filters[datetime][GREATER_THAN_OR_EQUAL_TO]" => $before,
            "fields[]" => "id",
            "Target" => "Conversion",
            "Method" => "findAll",
        ]);


    }

    private function makeUrl($request): string
    {
        $request = array_merge(
            ["NetworkToken" => self::API_TOKEN],
            $request
        );

        return self::API_BASE_URL . http_build_query($request);
    }

    public function findById($id): string
    {

        return $this->makeUrl([
            "id" => $id,
            "Target" => "Conversion",
            "Method" => "findById",
        ]);


    }

}
