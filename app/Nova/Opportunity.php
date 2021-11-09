<?php

namespace App\Nova;

use App\Helpers\StoreImageHelper;
use App\Models\Infrastructure\Country;
use App\Models\Infrastructure\Gender;
use App\Models\Infrastructure\Platform;
use App\Models\Infrastructure\TargetingParams;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use OptimistDigital\MultiselectField\Multiselect;

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
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    #TODO add db index
    public static $search = [
        'id', 'name', 'short_id'
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
            Text::make('Tune ID', 'external_id')
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
                Text::make('Time to Complete', 'timeToComplete')->default(0)
            ])->dependsOn('type', 'survey'),

            BelongsTo::make('Client'),


            Image::make('Image')
                ->disk('creatives')
                ->storeAs(function (Request $request) {
                    return StoreImageHelper::getCreativeAssetUniqueName($request->image);
                })
                ->prunable()
                ->rules('dimensions:min_width=640,min_height=360,max_width=640,max_height=360')
                ->help('Dimensions allowed: 640x360'),
            Text::make('CDN IMAGE URL', function () {
                $href = StoreImageHelper::getOpportunityAssetCDNUrl($this->resource);
                return $href
                    ? "<a href='" . $href . "'>CDN</a>"
                    : null;
            })->asHtml(),
            /*    ->storeAs(function (Request $request) {
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
                })*/

            Text::make('Url', 'link'),
            Textarea::make('Description')->alwaysShow(),
            Text::make('Call To Action'),
            Text::make('Incentive'),
            Multiselect::make('Platform', 'platforms')
                ->options(
                    Platform::all_flipped()
                )->default(Platform::values())
                ->placeholder("All")
                ->reorderable()
                ->saveAsJSON(),
            Multiselect::make('Gender', 'genders')->options(
                Gender::all_flipped()
            )->default(Gender::values())
                ->placeholder("All")
                ->reorderable()
                ->saveAsJSON(),
            Multiselect::make('Country', 'countries')->options(
                Country::all()
            )->default(null)
                ->placeholder("All")
                ->reorderable()
                ->saveAsJSON(),
            //Country::make('Countries', 'country_code')->default("All"),
            Number::make('Age from')->min(0)->max(130)->step(1),
            Number::make('Age to')->min(0)->max(130)->step(1),
            Multiselect::make('Required', 'targeting_params')->options(
                TargetingParams::all_flipped()
            )->default(TargetingParams::values())
                ->reorderable()
                ->saveAsJSON(),

            Number::make('Payout')->step(0.01)->default(0.00),
            Select::make('Currency')->options(
                ['USD' => 'USD']
            )->default('USD'),

            MorphToMany::make('Tags'),


            BelongsToMany::make('Widgets'),


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
