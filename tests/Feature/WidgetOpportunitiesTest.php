<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class WidgetOpportunitiesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_widget4()
    {
        $response = $this->json('GET', '/api/v1/widget/SVLSaRWEFhiTd6TVT4FjT/opportunities');
         // dd($response->json());

         $response->assertStatus(200);
         $response->assertJson(fn (AssertableJson $json) =>
             $json->has('items', 4)
                  ->has('items.0', fn ($json) =>
                     $json->where('id', "STePBK2WuOGOcfbyab-sR")
                          ->where('reward', "561.00")
                          ->etc()
                 )
                 ->has('items.2', fn ($json) =>
                 $json->where('id', "Aan7LGGfWhZz0IqeUZfCE")
                     ->where('reward', "2,592.15")
                     ->etc()
                 )




    );

    }
    public function test_widget2()
    {
        $response = $this->json('GET', '/api/v1/widget/Vhdpv2Jq6hk80A_kjBc2K/opportunities');
         //dd($response->json());

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
        $json->has('items', 8)
            ->has('items.3', fn ($json) =>
            $json->where('id', "auYzkuvln12C2yQyiw2zT")
                ->where('reward', "33.00")
                ->etc()
            )
            ->has('items.4', fn ($json) =>
            $json->where('id', "GrQTdz7VjJrNApBh2Lv_A")
                ->where('reward', "82.50")
                ->etc()
            )




        );

    }
}
