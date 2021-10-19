<?php

namespace App\Providers;

use App\Interfaces\DaliaPublisherAPIServiceIF;
use App\Interfaces\YoursurveysAPIServiceIF;
use App\Models\Conversion;
use App\Models\ConversionsHourlyStat;
use App\Models\Infrastructure\PrepareQueryBuilderWhere;
use App\Models\Integrations\DaliaOffers;
use App\Models\Integrations\Yoursurveys;
use App\Services\DaliaPublisherAPI\DaliaPublisherAPIService;
use App\Services\DaliaPublisherAPI\DaliaPublisherAPIServiceResponse;
use App\Services\TuneAPI\ConversionsHourlyStatsResponse;
use App\Services\TuneAPI\ConversionsResponse;
use App\Services\TuneAPI\TuneAPIService;
use App\Services\YoursurveysReadmeIoAPI\YoursurveysAPIService;
use App\Services\YoursurveysReadmeIoAPI\YourSurveysResponse;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
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
        if ($this->app->environment('local')) {
            //  'staging'  убрали пока , т.к прун не работает
            //   $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            //   $this->app->register(TelescopeServiceProvider::class);
        }

        if ($this->app->isLocal()) {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->bind(ConversionsResponse::class, function ($app, $params) {
            return new  ConversionsResponse(
            // auto inject rel model
                new Conversion()
            );

        });

        $this->app->bind(ConversionsHourlyStatsResponse::class, function ($app, $params) {
            return new  ConversionsHourlyStatsResponse(
                new ConversionsHourlyStat()
            );

        });

        $this->app->bind(DaliaPublisherAPIServiceResponse::class, function ($app, $params) {
            return new  DaliaPublisherAPIServiceResponse(
                new DaliaOffers()
            );
        });
        $this->app->bind(YourSurveysResponse::class, function ($app, $params) {
            return new  YourSurveysResponse(
                new Yoursurveys()
            );
        });


        $this->app->when(TuneAPIService::class)
            ->needs('$dateStart')
            ->give(function () {
                return $this->app->environment('local', 'staging')
                    ? now()->subDays(
                        config('services.tune_api.conversions_update_from_last_x_months')
                    )
                    : now()->subMonths(
                        config('services.tune_api.conversions_update_from_last_x_months')
                    );

            });

        //  100 per page for testing
        $this->app->when(TuneAPIService::class)
            ->needs('$per_page')
            ->give(function () {
                return $this->app->runningUnitTests()
                    ? 100
                    : null; // define by a const inside the service

            });


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


        $this->app->bind(DaliaPublisherAPIServiceIF::class, function () {
            return new DaliaPublisherAPIService(
                config('services.dalia.publisher_user_uuid'),
                new  DaliaPublisherAPIServiceResponse(
                    new DaliaOffers()
                )
            );


        });




    }
}
