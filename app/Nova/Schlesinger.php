<?php

namespace App\Nova;

use App\Models\Integrations\Schlesinger\SchlesingerIndustry;
use App\Models\Integrations\Schlesinger\SchlesingerSurvey;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;

class Schlesinger extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = SchlesingerSurvey::class;

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
            Number::make('SurveyId', 'SurveyId')->sortable(),
            Number::make('LanguageId', 'LanguageId'),
            Number::make('BillingEntityId', 'BillingEntityId')->nullable(),
            Number::make('CPI', 'CPI')->sortable(),
            Number::make('LOI', 'LOI')->sortable(),
            Number::make('IR', 'IR')->sortable(),
            Number::make('IndustryId', 'IndustryId')->sortable(),
            Text::make('Industry', function () {
                return SchlesingerIndustry::getNameById($this->IndustryId);
            })->exceptOnForms(),

            HasMany::make('qualifications', 'qualifications', SchlesingerSurveyQualification::class),

            Number::make('StudyTypeId', 'StudyTypeId')->sortable(),
            Boolean::make('IsMobileAllowed', 'IsMobileAllowed')->sortable(),
            Boolean::make('IsNonMobileAllowed', 'IsNonMobileAllowed')->sortable(),
            Boolean::make('IsSurveyGroupExist', 'IsSurveyGroupExist'),
            Boolean::make('CollectPII', 'CollectPII'),
            Number::make('AccountId', 'AccountId')->sortable(),
            Number::make('UrlTypeId', 'UrlTypeId'),
            DateTime::make('UpdateTimeStamp', 'UpdateTimeStamp')->sortable(),
            Boolean::make('IsManualInc', 'IsManualInc'),
            Boolean::make('IsQuotaLevelCPI', 'IsQuotaLevelCPI'),
            Text::make('LiveLink', 'LiveLink'),
            DateTime::make('Qual_UpdateTimeStamp', 'Qual_UpdateTimeStamp'),
            DateTime::make('Quota_UpdateTimeStamp', 'Quota_UpdateTimeStamp'),
            DateTime::make('Group_UpdateTimeStamp', 'Group_UpdateTimeStamp'),

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

    public static function label()
    {
        return 'Schlesinger';
    }

    public static function uriKey()
    {
        return "schlesinger";
    }
}
