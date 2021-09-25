<?php

namespace Tests\Feature;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class WidgetOpportunitiesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_widget4(): void
    {

        $response = $this->json('GET', '/api/v1/widget/SVLSaRWEFhiTd6TVT4FjT/opportunities');
        // dd($response->json());

        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->has('items', 4)
            ->has('items.0', fn($json) => $json->where('id', "STePBK2WuOGOcfbyab-sR")
                ->where('reward', 56100)
                ->whereType('reward', 'integer')
                ->whereType('required', 'array')
                ->where('required', [0 => "email", 1 => "country", 2 => "age"])
                ->where('targeting', [
                    "platform" => [
                        0 => "Mobile",
                        1 => "Tablet",
                    ],
                    "age" => [
                        "from" => null,
                        "to" => null,
                    ]])
                ->missing('targeting.0.country')
                ->missing('targeting.0.gender', [])
                ->etc()
            )
            ->has('items.2', fn($json) => $json->where('id', "Aan7LGGfWhZz0IqeUZfCE")
                ->where('reward', 259215)
                ->whereType('required', 'array')
                ->where('targeting',
                    [
                        "platform" => [
                            0 => "Mobile"
                        ],
                        "country" => [
                            0 => "Åland Islands",
                            1 => "Andorra",
                            2 => "Angola",
                            3 => "Argentina",
                            4 => "Azerbaijan",
                        ],
                        "gender" => [
                            0 => "Male",
                            1 => "Female"
                        ],
                        "age" => [
                            "from" => "8",
                            "to" => "89"
                        ]
                    ]
                )
                ->etc()
            )


        );

    }

    public function test_widget2(): void
    {
        $response = $this->json('GET', '/api/v1/widget/Vhdpv2Jq6hk80A_kjBc2K/opportunities');
        // dd($response->json());

        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->has('items', 8)
            ->has('items.3', fn($json) => $json->where('id', "auYzkuvln12C2yQyiw2zT")
                ->where('reward', 3300)
                ->whereType('reward', 'integer')
                ->etc()
            )
            ->has('items.4', fn($json) => $json->where('id', "GrQTdz7VjJrNApBh2Lv_A")
                ->where('reward', 8250)
                ->whereType('reward', 'integer')
                ->etc()
            )


        );

    }

    //TODO This database engine does not support JSON contains operations
    //https://devopsheaven.com/sqlite/databases/json/php/api/2019/11/26/sqlite-json-data-php.html
    // https://stackoverflow.com/questions/50535778/php-testing-json-contains-with-sqlite
    public function widget3()
    {
        /*         DB::getPdo()->sqliteCreateFunction('JSON_CONTAINS', function ($json, $val, $path = null) {
                    $array = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
                    // trim double quotes from around the value to match MySQL behaviour
                    $val = trim($val, '"');
                    // this will work for a single dimension JSON value, if more dimensions
                    // something more sophisticated will be required
                    // that is left as an exercise for the reader
                    if ($path) {
                        return $array[$path] == $val;
                    }

                    return in_array($val, $array, true);
                });*/

        $response = $this->json('GET', '/api/v1/widget/nGE1H6GuGNnW06tAZoxs5/opportunities');
        dd($response->json());

        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->has('items', 8)
            ->has('items.3', fn($json) => $json->where('id', "auYzkuvln12C2yQyiw2zT")
                ->where('reward', "33.00")
                ->etc()
            )
            ->has('items.4', fn($json) => $json->where('id', "GrQTdz7VjJrNApBh2Lv_A")
                ->where('reward', "82.50")
                ->etc()
            )


        );

    }
}


