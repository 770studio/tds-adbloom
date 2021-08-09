<?php

namespace App\Nova;

use App\Helpers\StoreImageHelper;
use App\Models\RedirectStatus;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Fields\DateTime;
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
            ID::make(__('ID'), 'id')->sortable(),
            Text::make('Name')
                ->rules('required')
                ->sortable(),
            Text::make('Short Id')->exceptOnForms(),
            Text::make('External Id')
                ->rules('required')
                ->sortable(),

            Boolean::make('Send Pending Postback', 'send_pending_postback'),

            Number::make('Pending Postback Timeout (days, hours on dev.env)', 'pending_timeout')->min(1)->max(30),

            Textarea::make('Postback URL', 'pending_url')->alwaysShow()->rows(3),
            Heading::make(
                view('partner_url_possible_macros')->render()
            )->asHtml(),

            BooleanGroup::make('Send Status Postback', 'send_pending_status')->options(
                RedirectStatus::indexes()
            ),


            new Panel('Revenue', $this->RevenueFields()),

            new Panel('Tags', $this->TagsFields()),

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

    protected function RevenueFields(): array
    {
        return [
            Toggle::make('Revenue Share', 'rev_share')->showOnIndex(false),
            NovaDependencyContainer::make([
                Number::make('Percentage, %', 'percentage')->min(1)->max(100)->step(1)
                    ->rules('required', 'gt:0'),
                Toggle::make('Convert to Points', 'convert_to_points'),
                NovaDependencyContainer::make([
                    Number::make('Multiplier', 'points_multiplier')->min(0.5)->max(9999999999)->step(0.5)
                        ->rules('required', 'gt:0'),
                    Text::make('Points Name', 'points_name'),

                ])->dependsOn('convert_to_points', 1),
            ])->dependsOn('rev_share', 1),


            Image::make('Points Logo', 'points_logo')
                ->disk('creatives')
                ->storeAs(function (Request $request) {
                    return StoreImageHelper::getCreativeAssetUniqueName($request->points_logo);
                })
                ->prunable()
                ->rules('mimes:gif,png,svg')
                ->help('Accepted: gif,png,svg'),

            Text::make('CDN points logo', function () {
                $href = StoreImageHelper::getPartnerPointsLogoAssetCDNUrl($this->resource);
                return $href
                    ? "<a href='" . $href . "'>CDN</a>"
                    : null;
            })->asHtml(),

        ];
    }

    protected function TagsFields(): array
    {
        return [
            MorphToMany::make('Tags'),
        ];
    }


}
