<?php

namespace App\Http\Controllers\Api;

use App\Helpers\RedirectHelper;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Widget;
use App\Services\GeneralResearchAPI\GeneralResearchAPIService;
use App\Services\GeneralResearchAPI\GeneralResearchResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

// TODO желательно как-то вынести контроллер из-под новы
class GRLController extends Controller
{

    /**
     * @return JsonResponse | RedirectResponse
     * exceptions handled via Handler.php
     * @throws Exception
     */
    public function redirect(Request $request, GeneralResearchAPIService $grlService)
    {
        Log::channel('queue')->debug('grl redirect accessed', $request->all());

        $validator = Validator::make($request->all(), [
            'tsid' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        Log::channel('queue')->debug('validation passed');

        return RedirectHelper::opportunity(
            $grlService->sendStatusToTune(
                $validator->validated()['tsid']
            )
        );


    }

    /**
     * @throws Exception
     * @route  api/v1/widget/{widget_short_id}/opportunities/grl
     */
    public function proxy(Request                   $request, string $widget_short_id,
                          GeneralResearchAPIService $grlService, GeneralResearchResponse $responseProcessor): JsonResponse
    {

        Log::channel('queue')->debug('grl proxy accessed');

        // partner is either in partnerId of the request or related to widget
        $partner = $request->partnerId
            ? Partner::where('external_id', $request->partnerId)->first()
            : Widget::where('short_id', $widget_short_id)->first()->partner;

        Log::channel('queue')->debug('partner found:' . $partner->external_id);


        return response()->json(
            $responseProcessor->setData(
                $grlService->setPartner($partner)->makeRequest()
            )->validate()
                ->transformDuration()
                ->transformPayouts($partner, true)
                ->transformUri()
                ->toArray()
            , 200, ["Cache-Control" => "no-store"], JSON_UNESCAPED_SLASHES
        );


    }

    public function UnmaskLink(Request $request)
    {
        //  dd($request->path(), $request->all());
        if (preg_match("/api\/v1\/go\/(.*)$/", $request->path(), $match)) {  // dd("https://task.generalresearch.com/" . $match[1] . "/?" . http_build_query($request->all() ));
            return redirect()->away(
                "https://task.generalresearch.com/api/v1/" . $match[1] . "/?" . http_build_query($request->all())
            );
        }

    }
}
