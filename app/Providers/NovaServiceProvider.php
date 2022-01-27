<?php

namespace App\Providers;

use App\Nova\Client;
use App\Nova\Conversion;
use App\Nova\DaliaOffers;
use App\Nova\Opportunity;
use App\Nova\Partner;
use App\Nova\SchlesingerSurveys;
use App\Nova\Tag;
use App\Nova\User;
use App\Nova\Widget;
use App\Nova\Yoursurveys;
use DigitalCreative\CollapsibleResourceManager\CollapsibleResourceManager;
use DigitalCreative\CollapsibleResourceManager\Resources\TopLevelResource;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;


class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {

            return true;
            return in_array($user->email, [
                'test@770.agency',
                'test@adbloom.co',
                'alexander@adbloom.com'
            ]);
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new Help,
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [

            new CollapsibleResourceManager([
                'disable_default_resource_manager' => true,
                'remember_menu_state' => true,
                'navigation' => [
                    TopLevelResource::make([
                        'label' => 'Resources',
                        'expanded' => true,
                        'resources' => [
                            User::class,
                            Conversion::class,
                            Client::class,
                            Opportunity::class,
                            Partner::class,
                            Widget::class,
                            Tag::class,
                        ]
                    ]),
                    TopLevelResource::make([
                        'label' => 'Integrations',
                        'expanded' => true,
                        'resources' => [
                            Yoursurveys::class,
                            DaliaOffers::class,
                            SchlesingerSurveys::class,
                        ],
                    ])


                ]
            ])
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
