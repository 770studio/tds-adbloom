<?php

namespace App\Nova;

use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use App\Helpers\StoreImageHelper;
use Laravel\Nova\Fields\Textarea;

class Opportunity extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Opportunity::class;

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
            Text::make('Name')
                ->rules('required')
                ->sortable(),
            Text::make('Short Id')->exceptOnForms(),
            Text::make('External Id')
                ->rules('required')
                ->sortable(),
            Select::make('Type')->options(
                self::$model::TYPES
            )//->resolveUsing(function () {
               // return $this->type ?? 'offer';
           // }) TODO this doesnt work
                ->rules('required')
                ->sortable(),

            NovaDependencyContainer::make([
                Text::make('Time to Complete', 'timeToComplete')
            ])->dependsOn('type', 'survey'),

            BelongsTo::make('Client'),


            Image::make('Image')
                ->disk('creatives')
                ->path($this->resource->id)
                ->storeAs(function (Request $request) {
                    $class = get_class($this->resource);

                    $class::saving(function ($model) use ($request) {
                        $model->image =  StoreImageHelper::getCreativeAssetUniqueName($this->resource->id, $request->image) ;
                        $request->image->storeAs($model->id
                            , $model->image
                            , 'creatives');
                        return  $model  ;
                    });

                    return $this->resource->image;
                })
                ->preview(function ($value, $disk) {
                    return $value
                        ? Storage::disk($disk)->url($this->resource->id . DIRECTORY_SEPARATOR. $value)
                        : null;
                })->prunable(),

            Textarea::make('Description'),
            Number::make('Payout '),
            Select::make('Currency')->options(
                ['USD']
            ),

            MorphToMany::make('Tags'),

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
            new Filters\DateTimeFilter('datetime'),
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
