<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WidgetOpportunitiesCollection;
use App\Models\Infrastructure\Country;
use App\Models\Infrastructure\Platform;
use App\Models\Opportunity;
use App\Models\Partner;
use App\Models\Widget;
use App\Services\GeneralResearchAPI\GeneralResearchAPIService;
use App\Services\GeneralResearchAPI\GeneralResearchResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class WidgetController extends Controller
{
    public function opportunities(Request $request, string $widget_short_id): WidgetOpportunitiesCollection
    {
        // get widget by id
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

        // override partner , by default partner is related to widget
        if ($request->partnerId) {
            Partner::setDefault($request->partnerId);
        }

        return new WidgetOpportunitiesCollection(
            $widget->opportunities()
                ->when(!$request->partnerId, function ($q) {
                    $q->with('widgets.partner'); // by default partner is related to widget
                })
                ->get()
        );
    }

    /**
     * @throws Exception
     */
    public function grl(Request                   $request, string $widget_short_id,
                        GeneralResearchAPIService $grlService, GeneralResearchResponse $responseProcessor)
    {
        try {
            // partner is either in partnerId of the request or related to widget
            $partner = $request->partnerId
                ? Partner::where('external_id', $request->partnerId)->first()
                : Widget::where('short_id', $widget_short_id)->first()->partner;


            return response()->json(
                $responseProcessor->setData(
                    $grlService->setPartner($partner)->makeRequest()
                )->validate()
                    ->transformResponse($partner)
                , 200, [], JSON_UNESCAPED_SLASHES);


        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'errorMessage' => Str::substr($e->getMessage(), 0, 50) . '...'
            ]);
        }


    }


}
