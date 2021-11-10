<?php

namespace App\Nova;

use App\Helpers\WidgetJSTemplateHelper;
use App\Models\Infrastructure\Country;
use App\Models\Infrastructure\Platform;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Naif\Toggle\Toggle;
use OptimistDigital\MultiselectField\Multiselect;
use Sixlive\TextCopy\TextCopy;

//use Everestmx\BelongsToManyField\BelongsToManyField;


class Widget extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Widget::class;

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
    public static $polling = false;
    /**
     * Indicates whether to show the polling toggle button inside Nova.
     *
     * @var bool
     */
    public static $showPollingToggle = false;
    public static $pollingInterval = 600; // 10 minutes
    /**
     * Default ordering for index query.
     *
     * @var array
     */
    public static $indexDefaultOrder = [
        'id' => 'desc'
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
            ID::make('ID', 'id')->sortable(),
            //BelongsToMany::make('opportunities'),
            //AttachMany::make( 'Opportunities' )->fields,
            //AttachMany::make('name', 'relationshipName', RelatedResource::class);
            Text::make('Short Id')->exceptOnForms(),
            BelongsTo::make('Partner'),
            Toggle::make('Dynamic | Static', 'dynamic_or_static')
                ->help("Static when this is on otherwise Dynamic (by default)")
                ->default(0),

            /*            Select::make('Dynamic / Static', 'dynamic_or_static' )  ->options([
                            '0' => 'Dynamic',
                            '1' => 'Static',
                        ])->default(0)
                            ->displayUsingLabels(),
                        */
// Must be able to specify Tags, Platforms, Countries when Dynamic is enabled.
// In this case Opportunities for this widget will be selected based on these criteria.
            NovaDependencyContainer::make([
                Multiselect::make('Tag', 'tags')->options(
                    \App\Models\Tag::whereHas('opportunities')->pluck('name', 'id')
                )->saveAsJSON(),
                Multiselect::make('Platform', 'platforms')->options(
                    Platform::all_flipped()
                )->default(Platform::values())
                    ->placeholder("All")
                    ->saveAsJSON(),
                Multiselect::make('Country', 'countries')->options(
                    Country::all()
                )->default(null)
                    ->placeholder("All")
                    ->saveAsJSON(),

            ])->dependsOn('dynamic_or_static', 0),
            NovaDependencyContainer::make([
                Multiselect::make('Opportunities', 'opportunities')
                    ->options(\App\Models\Opportunity::all())
                    ->belongsToMany(Opportunity::class)


            ])->dependsOn('dynamic_or_static', 1),

            new Panel('Integration', $this->IntegrationFields()),


        ];
    }

    protected function IntegrationFields(): array
    {
        return [
            Heading::make('<hr><b>Link</b>')->asHtml()->onlyOnDetail(),
            Code::make('', function () {
                return sprintf("%s/?widgetId=%s&partnerId=%d",
                    config('app.url'),
                    $this->short_id,
                    $this->partner->external_id
                );
            })->language('javascript')
                ->height(30)->onlyOnDetail(),
            TextCopy::make('Copy to clipboard', function () {
                return sprintf(" %s/?widgetId=%s&partnerId=%d",
                    config('app.url'),
                    $this->short_id,
                    $this->partner->external_id
                );
            })->truncate(1)
                ->copyValue(function ($value) {
                    return trim($value);
                })->copyButtonTitle('Copy the link into clipboard')->onlyOnDetail(),

            Heading::make('<hr><b>On-page</b>')->asHtml()->onlyOnDetail(),
            Code::make('Add this code right before the </head> tag of the HTML page. ', function () {
                return WidgetJSTemplateHelper::getTpl($this->partner->external_id, $this->short_id);
            })->language('javascript')->onlyOnDetail(),
            TextCopy::make('Copy to clipboard', function () {
                return WidgetJSTemplateHelper::getTpl($this->partner->external_id, $this->short_id);
            })->truncate(1)
                ->copyValue(function ($value) {
                    return trim($value);
                })->copyButtonTitle('Copy the code into clipboard')->onlyOnDetail(),
            Code::make('Add this code to the place where you want to display the widget. ', function () {
                return '<div id="adblm-widget"></div>';
            })->language('javascript')
                ->height(10)->onlyOnDetail(),
            TextCopy::make('Copy to clipboard', function () {
                return ' <div id="adblm-widget"></div>';
            })->truncate(1)
                ->copyValue(function ($value) {
                    return trim($value);
                })->copyButtonTitle('Copy the code into clipboard')->onlyOnDetail(),


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
            new Filters\DateTimeFilter(),
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
     * Build an "index" query for the given resource.
     *
     * @param NovaRequest $request
     * @param Builder $query
     * @return Builder
     */
    public static function indexQuery(NovaRequest $request, $query): Builder
    {
        if (empty($request->get('orderBy'))) {
            $query->getQuery()->orders = [];

            return $query->orderBy(key(static::$indexDefaultOrder), reset(static::$indexDefaultOrder));
        }

        return $query;
    }


}
