<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WidgetOpportunitiesCollection;
use App\Models\Opportunity;
use App\Models\Widget;

class WidgetController extends Controller
{
    public function opportunities($widget_short_id)
    {

        $widget = Widget::where('short_id', $widget_short_id)->firstOrFail();

        if ($widget->isDynamic()) {
            $opportunities = Opportunity::where(function ($query) use ($widget) {
                return $query
                    ->when($widget->platforms, function ($query) use ($widget) {
                        return $query->whereJsonContains('platforms', $widget->platforms);
                    })
                    ->when($widget->countries, function ($query) use ($widget) {
                        return $query->whereJsonContains('countries', $widget->countries);
                    });
                /*             ->when($widget->tags, function ($query) use ($widget) {
                                 return $query->whereJsonContains('tags', $widget->tags);
                             });*/

            })
                ->get();

            dd($opportunities);

            dd($widget->platforms, $widget->countries, $widget->tags);
            // $opportunities =
        } else {
            $opportunities = $widget->opportunities;
        }
        //$widgetOrts = $widget->isDynamic()
        return new WidgetOpportunitiesCollection(
            $opportunities
        );
    }
}
