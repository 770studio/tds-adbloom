<?php

namespace App\Providers;

use App\Interfaces\YoursurveysAPIServiceIF;
use App\Services\YoursurveysReadmeIoAPI\YoursurveysAPIService;
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
        if ($this->app->environment('local', 'staging')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
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

        $this->app->bind(YoursurveysAPIServiceIF::class, function () {
            if (app()->runningInConsole()) {
                // 0 => "artisan"
                // 1 => "yoursurveys:update"
                // 2 => ...
                switch (optional($_SERVER['argv'])[1]) {
                    case 'yoursurveys:update':
                        unset($_SERVER['argv'][0], $_SERVER['argv'][1]);
                        return new YoursurveysAPIService(...$_SERVER['argv']);
                }

            }

        });

    }
}
