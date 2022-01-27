<?php

namespace App\Nova;

use App\Models\Integrations\Schlesinger;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;

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
            ID::make(__('ID'), 'SurveyId')->sortable(),
            Number::make('', 'LanguageId'),
            Number::make('', 'BillingEntityId')->nullable(),
            Number::make('', 'CPI'),
            Number::make('', 'LOI'),
            Number::make('', 'IR'),
            Number::make('', 'IndustryId'),
            Number::make('', 'StudyTypeId'),
            Boolean::make('', 'IsMobileAllowed'),
            Boolean::make('', 'IsNonMobileAllowed'),
            Boolean::make('', 'IsSurveyGroupExist'),
            Boolean::make('', 'CollectPII'),
            Number::make('', 'AccountId'),
            Number::make('', 'UrlTypeId'),
            DateTime::make('', 'UpdateTimeStamp'),
            Boolean::make('', 'IsManualInc'),
            Boolean::make('', 'IsQuotaLevelCPI'),
            Text::make('', 'LiveLink'),
            DateTime::make('', 'Qual_UpdateTimeStamp'),
            DateTime::make('', 'Quota_UpdateTimeStamp'),
            DateTime::make('', 'Group_UpdateTimeStamp'),

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
