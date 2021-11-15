<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WidgetOpportunitiesCollection;
use App\Http\Resources\WidgetOpportunitiesResource;
use App\Models\Infrastructure\Country;
use App\Models\Infrastructure\Platform;
use App\Models\Opportunity;
use App\Models\Partner;
use App\Models\Widget;
use App\Services\GeneralResearchAPI\GeneralResearchAPIService;
use App\Services\GeneralResearchAPI\GeneralResearchResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class WidgetController extends Controller
{
    /**
     * @return WidgetOpportunitiesCollection | JsonResponse
     * @throws Exception
     * exceptions handled via Handler.php
     */
    public function opportunities(Request                   $request, string $widget_short_id,
                                  GeneralResearchAPIService $grlService,
                                  GeneralResearchResponse   $grlResponseProcessor)
    {

        /**
         * @var Widget $widget
         *   get widget by id
         */
        $widget = Widget //::with('partner')
        ::where('short_id', $widget_short_id)
            ->firstOrFail();

        // if widget is dynamic, get opportunities by widget dynamic params and attach these opportunities to widget
        if ($widget->isDynamic()) {

            if ($widget->countries === Country::indexes()) {
                $widget->countries = null; // same as all (including undefined opportunity country)
            }

            if (count($widget->platforms) === count(Platform::indexes())) {
                $widget->platforms = null; // same as all (including undefined opportunity country)
            }

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


        /*        DB::listen(function ($query) {
                    $sql = $query->sql;
                    $bindings = $query->bindings;
                    $executionTime = $query->time;

                    dump($sql);

                });*/

        /**
         * override partner , by default partner is related to widget
         * @var Partner $partner
         */
        $partner = WidgetOpportunitiesResource::$partner = $request->partnerId
            ? Partner::where('external_id', $request->partnerId)->first()
            : $widget->partner;


        // подмешать временно! TODO убрать
        $mixin = $grlResponseProcessor->setData(
            $grlService->setPartner($partner)->makeRequest()
        )->validate()
            ->getBucket();


        return new WidgetOpportunitiesCollection(
            $widget->opportunities()
                ->get()
                // TODO убрать временный mixin
                ->push(new Opportunity($mixin))
                ->filter(function ($collection) {
                    return $collection->short_id;
                })
        );

    }


}
