<?php

namespace App\Http\Controllers\Api;

use App\Helpers\StoreImageHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\WidgetOpportunitiesCollection;
use App\Http\Resources\WidgetOpportunitiesResource;
use App\Models\Infrastructure\Country;
use App\Models\Infrastructure\Platform;
use App\Models\Opportunity;
use App\Models\Widget;
use App\Services\GeneralResearchAPI\GeneralResearchAPIService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Throwable;


class WidgetController extends Controller
{
    /**
     * @return WidgetOpportunitiesCollection | JsonResponse
     * @throws Exception
     * exceptions handled via Handler.php
     */
    public function opportunities(Request $request, string $widget_short_id, GeneralResearchAPIService $grlService)
    {

        /**
         * @var Widget $widget
         *   get widget by id
         */
        $widget = Widget //
        ::with('partner')
            ->where('short_id', $widget_short_id)
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

        try {
            /**
             *  declare mixin here
             *  so if we get into any exception  catch it, report it (log), then return response with no mixin
             * @var $mixin Collection
             */
            $mixin = collect();

            $grlService->setWidget($widget);

            //TODO static shit
            WidgetOpportunitiesResource::$partner = $partner = $grlService->getPartner();
            // подмешать временно! TODO убрать
            if ($widget->enable_grl_inventory) {
                $mixin = $grlService->makeRequest()
                    ->validate()
                    ->transformPayouts($partner)
                    ->transformUri()
                    ->getBuckets(5);
            }


        } catch (Throwable $e) {
            report($e);
        }

        return response()->json(
            ['options' => [
                "enableGrlInventory" => (bool)$widget->enable_grl_inventory,
                "logoUrl" => StoreImageHelper::getPartnerLogo($partner)
            ],
                'items' => (new WidgetOpportunitiesCollection  (
                    $mixin->merge(
                        $widget->opportunities()
                            ->get()
                    )->filter(function ($collection) {
                        return $collection->short_id;
                    })
                ))], 200, ["Cache-Control" => "no-store"], JSON_UNESCAPED_SLASHES);

    }


}
