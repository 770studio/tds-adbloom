<?php

namespace App\Http\Controllers\Api;

use App\Helpers\RedirectHelper;
use App\Http\Controllers\Controller;
use App\Models\Widget;
use App\Services\GeneralResearchAPI\GeneralResearchAPIService;
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

        $status_object = $grlService->checkStatus(
            $validator->validated()['tsid']
        );

        $grlService->sendStatusToTune($status_object);

        $widget = $status_object->getWidget();
        return $widget
            ? RedirectHelper::widget($widget, $status_object->getClickID(), $status_object->getStatus())
            : RedirectHelper::opportunity($status_object->getStatus());


    }

    /**
     * @throws Exception
     * @route  api/v1/widget/{widget_short_id}/opportunities/grl
     */
    public function proxy(Request                   $request, string $widget_short_id,
                          GeneralResearchAPIService $grlService): JsonResponse
    {
        Log::channel('queue')->debug('grl proxy accessed');

        $widget = Widget::findByShortId($widget_short_id)
            ->with('partner')
            ->firstOr(function () use ($widget_short_id) {
                Log::channel('queue')->debug('widget not found:' . $widget_short_id);
                throw new Exception('widget not found');
            });
        Log::channel('queue')->debug('widget found:' . $widget->short_id);

        return response()->json(
            $grlService->setWidget($widget)
                ->makeRequest()
                ->validate()
                ->transformDuration()
                ->transformPayouts($grlService->getPartner(), true)
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
