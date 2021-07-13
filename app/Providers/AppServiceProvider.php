<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Tune\NetworkApi;
use Tune\Networks;
use Tune\Utils\Network;


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
