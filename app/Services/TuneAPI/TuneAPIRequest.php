<?php


namespace App\Services\TuneAPI;



class TuneAPIRequest
{

    const API_TOKEN = "NETzcmyVZWooz2oPYnlWDzOu9kiQmD";
    const API_BASE_URL = "https://adbloom.api.hasoffers.com/Apiv3/json?";

    public function __construct()
    {

    }

    public function findAll($before) {

       return $this->makeUrl([
           "filters[datetime][GREATER_THAN_OR_EQUAL_TO]" => $before,
           "fields[]"=>"id",
           "Target"=>"Conversion",
           "Method"=>"findAll",
       ]);



    }

    private function makeUrl($request) : string
    {
        $request = array_merge(
                ["NetworkToken" => self::API_TOKEN],
                    $request
        ) ;

        return self::API_BASE_URL . http_build_query($request);
    }

}
