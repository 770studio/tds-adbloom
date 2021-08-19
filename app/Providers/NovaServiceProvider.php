<?php

namespace App\Providers;

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
                            \App\Nova\User::class,
                            \App\Nova\Conversion::class,
                            \App\Nova\Client::class,
                            \App\Nova\Opportunity::class,
                            \App\Nova\Partner::class,
                            \App\Nova\Widget::class,
                            \App\Nova\Tag::class,
                        ]
                    ]),
                    TopLevelResource::make([
                        'label' => 'Integrations',
                        'expanded' => true,
                        'resources' => [
                            \App\Nova\Yoursurveys::class,
                            \App\Nova\DaliaOffers::class,
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
