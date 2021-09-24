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

        $widget = Widget //::with('partner')
        ::where('short_id', $widget_short_id)
            ->firstOrFail();


        if ($widget->isDynamic()) {
            //dd($widget->platforms, $widget->countries, $widget->tags);
                            // Opportunity::
            $opportunities = Opportunity::select('id') //DB::table('opportunities')
            ->where(function ($query) use ($widget) {
                return $query
                    ->when($widget->countries, function ($query) use ($widget) {
                        foreach ($widget->countries as $country) {
                            $query->orWhereJsonContains('countries', $country);
                        }
                    });
            })
                ->where(function ($query) use ($widget) {
                    return $query
                        ->when($widget->platforms, function ($query) use ($widget) {
                            foreach ($widget->platforms as $platform) {
                                $query->orWhereJsonContains('platforms', $platform);
                            }
                        });
                })
                ->when($widget->tags, function ($query) use ($widget) {
                    return $query
                        ->whereHas('tags', function ($query) use ($widget) {
                            $query->whereIn('id', $widget->tags);
                        });
                })
                ->get();

            $widget->opportunities()->sync($opportunities->pluck('id'));

        } else {

            // $opportunities = $widget->opportunities;
        }


        //$widgetOrts = $widget->isDynamic()
        // dd($opportunities);
        // dd($opportunities->merge(['partner' => $widget->Partner ]));

        //dd($widget );
        return new WidgetOpportunitiesCollection(
            $widget->opportunities()->with('widgets.partner')->get()
        );
    }
}
