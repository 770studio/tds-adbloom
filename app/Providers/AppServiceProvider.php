<?php

namespace App\Providers;

use App\Interfaces\TuneAPI;
use Illuminate\Support\ServiceProvider;
use Tune\NetworkApi;
use Tune\Networks;
use Tune\Tune;
use Tune\Utils\Network;
use Tune\Utils\UseApiCalls;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {


        $this->app->bind(NetworkApi::class, function () {
            return new NetworkApi(new Networks([
                new Network(
                    config('services.tune_api.key'),
                    config('services.tune_api.network_id')
                ), // Auto selected network
            ]));

        });


    }
}
