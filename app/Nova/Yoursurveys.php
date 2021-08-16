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


class Yoursurveys extends Resource
{




    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Integrations\Yoursurveys::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'project_id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'json'
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

    /**
     * Apply the search query to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected static function applySearch($query, $search)
    {

        return $query->where(function ($query) use ($search) {
            $model = $query->getModel();

            $connectionType = $model->getConnection()->getDriverName();

            $canSearchPrimaryKey = ctype_digit($search) &&
                in_array($model->getKeyType(), ['int', 'integer']) &&
                ($connectionType != 'pgsql' || $search <= static::maxPrimaryKeySize()) &&
                in_array($model->getKeyName(), static::$search);

            if ($canSearchPrimaryKey) {
                $query->orWhere($model->getQualifiedKeyName(), $search);
            }


            $query->whereJsonContains('json->name', $search);


        });
    }
}
