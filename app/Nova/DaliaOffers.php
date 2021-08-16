<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Naoray\NovaJson\JSON;


class DaliaOffers extends Resource
{

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Integrations';
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Integrations\DaliaOffers::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'uuid';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'uuid', 'title', 'info_short', 'info'
    ];


    /**
     * Default ordering for index query.
     *
     * @var array
     */
    public static $indexDefaultOrder = [
        'created_at' => 'desc'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Text::make('Project Id')
                ->rules('required')
                ->sortable(),

            JSON::make("json", [
                Text::make('name'),
                Number::make('study_type'),
                Number::make('cpi'),
                Number::make('remaining_completes'),
                Number::make('conversion_rate'),
                Number::make('loi'),
                Text::make('country'),
                Text::make('survey_groups_ids'),
                Text::make('platform_types'),
                Boolean::make('match_to_qualify'),
                Boolean::make('delay_crediting'),
                Boolean::make('tentative_payout'),

                JSON::make('Order', [

                    Number::make('Order loi', 'loi'),
                    Number::make('Order ir', 'ir'),

                ]),

                Boolean::make('is_pmp'),
                Text::make('entry_link'),
                Text::make('score'),


            ]),

            DateTime::make('Created at')->sortable()->exceptOnForms(),
            DateTime::make('Updated at')->sortable()->exceptOnForms(),
        ];
    }


    /**
     * Get the cards available for the request.
     *
     * @param Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }


}
