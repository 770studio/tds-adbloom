<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;

class Conversion extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Conversion::class;

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
    public static $polling = true;
    /**
     * Indicates whether to show the polling toggle button inside Nova.
     *
     * @var bool
     */
    public static $showPollingToggle = true;
    public static $pollingInterval = 600; // 10 minutes
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
            ID::make('ID', 'id')->sortable(),
            Text::make('Offer name', 'Offer_name')->sortable(),
            Text::make('Stat Payout', 'Stat_payout')->sortable(),
            Text::make('Stat Revenue', 'Stat_revenue')->sortable(),
            Text::make('Goal Name', 'Goal_name')->sortable(),
            Text::make('Stat Status', 'Stat_status')->sortable(),
            DateTime::make('Updated', 'updated_at')->sortable(),

        ];

        $fields[] = ID::make('ID', 'id')->sortable();

        foreach (\App\Models\Conversion::FIELDS as $field) {
            $field_name = Str::replaceFirst('.', '_', $field);
            $human_field_name = Str::replace(['.', '_'], ' ', $field);
            switch ($field) {
                case 'Stat.tune_event_id':
                    $fields[] = Text::make($human_field_name, $field_name)->sortable();
                         break;

                 //    index page sortable date

                 case 'Stat.date':
                 case 'Stat.session_date':
                 case 'Stat.datetime':
                 case 'Stat.session_datetime':

                    $fields[] =  DateTime::make($human_field_name,$field_name)->sortable();
                         break;
                 // index page sortable decimal
                 case 'Stat.approved_payout':
                 case 'Stat.approved_rate':
                 case 'Stat.net_payout':
                 case 'Stat.net_revenue':
                 case 'Stat.net_sale_amount':
                 case 'Stat.payout':
                 case 'Stat.pending_payout':
                 case 'Stat.pending_revenue':
                 case 'Stat.pending_sale_amount':
                 case 'Stat.rejected_rate':
                 case 'Stat.revenue':
                 case 'Stat.sale_amount':


                    $fields[] = Number::make($human_field_name,$field_name)->sortable();

                    break;
                 // index page sortable other
                 case 'Affiliate.company':
                 case 'Advertiser.company':
                 case 'Browser.display_name':
                 case 'Stat.id':
                 case 'Stat.ip':
                 case 'Stat.offer_id':
                 case 'Stat.status':
                 case 'Stat.status_code':
                    $fields[] = Text::make($human_field_name, $field_name)->sortable();

                 break;


                 default:
                     $fields[] = Text::make($human_field_name,$field_name)
                         ->hideFromIndex();

             }
        }


        $fields[] =  DateTime::make('Created', 'created_at')->sortable();
        $fields[] =  DateTime::make('Updated', 'updated_at')->sortable();



        return $fields;
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
}
