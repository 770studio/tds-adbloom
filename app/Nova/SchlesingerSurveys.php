<?php

namespace App\Nova;

use App\Models\Integrations\Schlesinger;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;

class SchlesingerSurveys extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Schlesinger::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'SurveyId';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = ['SurveyId'];


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
            /*   Text::make('uuid'),
               Text::make('title'),
               Text::make('info'),
               Text::make('info_short'),
               JSON::make("json", [
                   Text::make('reward_dollar'),
                   Text::make('tag_list'),
                   Text::make('target_groups'),
                   Text::make('device_kinds'),
                   Number::make('total_entries'),
                   Number::make('total_completions'),
                   Number::make('estimated_duration_minutes'),
                   Number::make('max_duration_minutes'),
                   Text::make('url'),


               ]),*/

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
