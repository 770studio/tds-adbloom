<?php

namespace App\Nova;

use App\Helpers\StoreImageHelper;
use App\Models\Infrastructure\RedirectStatus;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Panel;
use Naif\Toggle\Toggle;

class Partner extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Partner::class;

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
            Image::make('Logo')
                ->prunable()
                ->rules('mimes:png,svg', 'dimensions:min_width=120,min_height=120,max_width=120,max_height=120')
                ->help('Accepted: png,svg <br>Dimensions: 120x120'),
            ID::make(__('ID'), 'id')->sortable(),
            Text::make('Name')
                ->rules('required')
                ->sortable(),
            Text::make('Short Id')->exceptOnForms(),
            Text::make('Tune ID', 'external_id')
                ->rules('required')
                ->sortable(),

            new Panel('Postback', $this->PostbackFields()),
            new Panel('Revenue', $this->RevenueFields()),
            new Panel('Tags', $this->TagsFields()),
            new Panel('Widgets', $this->WidgetFields()),


            DateTime::make('Created at')->sortable()->onlyOnDetail(),
            DateTime::make('Updated at')->sortable()->onlyOnDetail(),


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

    protected function PostbackFields(): array
    {
        return [
            Textarea::make('Postback URL', 'pending_url')->alwaysShow()->rows(3),
            Heading::make(
                view('partner_url_possible_macros')->render()
            )->asHtml(),
            BooleanGroup::make('Send Status Postback', 'send_pending_status')->options(
                RedirectStatus::indexes()
            ),
            Toggle::make('Send Pending Postback', 'send_pending_postback')->onColor('green'),
            NovaDependencyContainer::make([
                Number::make('Pending Postback Timeout (days, hours on dev.env)', 'pending_timeout')->min(1)->max(30),

            ])->dependsOn('send_pending_postback', 1),


        ];
    }


    protected function RevenueFields(): array
    {

        $pointsLogoHref = StoreImageHelper::getPartnerPointsLogoAssetCDNUrl($this->resource);

        return [
            Toggle::make('Revenue Share', 'rev_share')->showOnIndex(false),
            NovaDependencyContainer::make([
                Number::make('Percentage, %', 'percentage')->min(1)->max(100)->step(1),
                // ->rules('required', 'gt:0'),
                Toggle::make('Convert to In-app Currency', 'convert_to_points')->onColor('green'),
                NovaDependencyContainer::make([
                    Number::make('Reward users this amount for each $1 earned', 'points_multiplier')->min(0.5)->max(9999999999)->step(0.5),
                    //->rules('required', 'gt:0'), Reward users [input] for each $1 earned
                    Text::make('In-app Currecny Name', 'points_name'),
                    Image::make('In-app Currency Icon', 'points_logo')
                        ->disk('creatives')
                        ->storeAs(function (Request $request) {
                            return StoreImageHelper::getCreativeAssetUniqueName($request->points_logo);
                        })
                        ->prunable()
                        ->rules('mimes:gif,png,svg')
                        ->help('Accepted: gif,png,svg')
                        ->help($pointsLogoHref
                            ? "<a href='" . $pointsLogoHref . "'>CDN</a>"
                            : ''),


                ])->dependsOn('convert_to_points', 1),
            ])->dependsOn('rev_share', 1),




        ];
    }

    protected function TagsFields(): array
    {
        return [
            MorphToMany::make('Tags'),
        ];
    }
    protected function WidgetFields(): array
    {
        return [
            HasMany::make('Widgets'),
        ];
    }

}
