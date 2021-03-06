<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class SchlesingerSurveyQualification extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Integrations\Schlesinger\SchlesingerSurveyQualification::class;

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

    public static $with = ['question', 'answers'];


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
            Text::make('AnswerIds', function () {
                return implode(",", $this->AnswerIds);
            }),
            Text::make('AnswerCodes', function () {
                return implode(",", $this->AnswerCodes);
            }),

            Text::make('Name', function () {
                return $this->question->name;
            }),
            Text::make('Text', function () {
                return $this->question->text;
            }),

            Text::make('Answers', function () {
                /** @var Collection $answers */
                $answers = $this->answers;
                return $answers->isNotEmpty()
                    ? $answers->pluck('text')->implode(',')
                    : '';
            }),

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
        return [];
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
