<?php

namespace App\Http\Controllers;

use App\Helpers\RedirectHelper;
use App\Models\Partner;
use App\Models\Widget;
use App\Services\GeneralResearchAPI\GeneralResearchAPIService;
use App\Services\GeneralResearchAPI\GeneralResearchResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

// TODO желательно как-то вынести контроллер из-под новы
class GRLController extends Controller
{

    /**
     * @throws Exception
     */
    public function redirect(Request $request, GeneralResearchAPIService $grlService)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tsid' => 'required',
            ]);

            if ($validator->fails()) {
                return response($validator->errors(), 422);
            }

            RedirectHelper::opportunity(
                $grlService->sendStatusToTune(
                    $validator->validated()['tsid']
                )
            );

        } catch (Exception $e) {
            //TODO refactor to an error resource like : return JsonErrorResourceCollection($errors)
            //TODO logging
            return response()->json([
                'status' => 'error',
                'errorMessage' => Str::substr($e->getMessage(), 0, 50) . '...',
            ]);
        }


    }

    /**
     * @throws Exception
     */
    public function proxy(Request                   $request, string $widget_short_id,
                          GeneralResearchAPIService $grlService, GeneralResearchResponse $responseProcessor): JsonResponse
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
                    ->transformPayouts($partner)
                    ->toArray()
                , 200, [], JSON_UNESCAPED_SLASHES
            );

        } catch (Exception $e) {
            //TODO refactor to an error resource like : return JsonErrorResourceCollection($errors)
            //TODO logging
            return response()->json([
                'status' => 'error',
                'errorMessage' => Str::substr($e->getMessage(), 0, 50) . '...',
            ]);
        }


    }

}
