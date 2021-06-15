<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Conversion extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Conversion::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Indicates whether the resource should automatically poll for new resources.
     *
     * @var bool
     */
    public static $polling = true;
    /**
     * Indicates whether to show the polling toggle button inside Nova.
     *
     * @var bool
     */
    public static $showPollingToggle = true;
    public static $pollingInterval = 30;
    /**
     * Default ordering for index query.
     *
     * @var array
     */
    public static $indexDefaultOrder = [
        'datetime' => 'desc'
    ];
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            ID::make(  'status')->sortable(),
            ID::make(  'status_code')->hideFromIndex(),
            DateTime::make('Created at' )->sortable(),
            DateTime::make('Updated at')->sortable(),
            Text::make('Affiliate id'),
            Text::make('datetime')->sortable(),
            Text::make('batch_date_utc')->hideFromIndex(),
            Text::make('offer_id'),
            Text::make('batch_timestamp')->hideFromIndex(),
            Text::make('payout'),
            Text::make('revenue'),
            Text::make('ad_id')->hideFromIndex(),
            Text::make('tune_event_id')->hideFromIndex(),
            Text::make('advertiser_manager_id')->hideFromIndex(),
            Text::make('advertiser_id')->hideFromIndex(),
            Text::make('affiliate_manager_id')->hideFromIndex(),
            Text::make('goal_id')->hideFromIndex(),
            Text::make('creative_url_id')->hideFromIndex(),
            Text::make('customer_id')->hideFromIndex(),
            Text::make('source'),
            Text::make('affiliate_info1')->hideFromIndex(),
            Text::make('affiliate_info2')->hideFromIndex(),
            Text::make('affiliate_info3')->hideFromIndex(),
            Text::make('affiliate_info4')->hideFromIndex(),
            Text::make('affiliate_info5')->hideFromIndex(),
            Text::make('advertiser_info')->hideFromIndex(),
            Text::make('session_datetime')->hideFromIndex(),
            Text::make('refer')->hideFromIndex(),
            Text::make('pixel_refer')->hideFromIndex(),
            Text::make('ip'),
            Text::make('session_ip')->hideFromIndex(),
            Text::make('sale_amount'),
            Text::make('user_agent'),
            Text::make('country_code'),
            Text::make('event_city')->hideFromIndex(),
            Text::make('event_region')->hideFromIndex(),
            Text::make('browser_id')->hideFromIndex(),
            Text::make('is_adjustment')->hideFromIndex(),
            Text::make('ad_campaign_id')->hideFromIndex(),
            Text::make('ad_campaign_creative_id')->hideFromIndex(),
            Text::make('offer_file_id')->hideFromIndex(),
            Text::make('payout_type')->hideFromIndex(),
            Text::make('revenue_type')->hideFromIndex(),
            Text::make('currency'),
            Text::make('promo_code')->hideFromIndex(),
            Text::make('adv_unique1')->hideFromIndex(),
            Text::make('adv_unique2')->hideFromIndex(),
            Text::make('adv_unique3')->hideFromIndex(),
            Text::make('adv_unique4')->hideFromIndex(),
            Text::make('adv_unique5')->hideFromIndex(),
            Text::make('order_id')->hideFromIndex(),
            Text::make('sku_id')->hideFromIndex(),
            Text::make('product_category')->hideFromIndex(),
            Text::make('app_version')->hideFromIndex(),


        ];
    }









    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new Filters\DateTimeFilter('datetime'),
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
