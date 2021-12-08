<?php

namespace App\Http\Resources;

use App\Helpers\ArrayHelper;
use App\Helpers\StoreImageHelper;
use App\Models\Infrastructure\Gender;
use App\Models\Infrastructure\Platform;
use App\Models\Infrastructure\TargetingParams;
use App\Models\Opportunity;
use App\Models\Partner;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class WidgetOpportunitiesResource extends JsonResource
{


    // TODO get rid of that
    public static Partner $partner;


    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {

        /**
         * @var Opportunity $this
         */
        $widget = $this->pivot->pivotParent ?? null;
        $image = StoreImageHelper::getCreativesCDNUrl($this->image);
        $reward = self::$partner->calulateReward($this->payout);
        $targeting_params = TargetingParams::collection()->only((array)$this->targeting_params)->values();

        /*  if("MIZzRZtPRlxu1SiSnghAn" == $this->short_id) {
              dd($this->platforms,
                  Platform::collection()->only([])
              );
          }*/
        $targeting = ArrayHelper::stackNotEmpty(
            [
                'platform' => Platform::collection()->only((array)$this->platforms)->values(),
                'country' => $this->countries,
                'gender' => Gender::collection()->only((array)$this->genders)->values(),
                'age' => $this->getAgeFromTo()
            ]
        );


        //TODO remove unnecessary `when`
        return [
            'id' => $this->short_id,
            'title' => $this->when($this->name, $this->name),
            //'img' => $this->image,
            'image' => $this->when($image, $image),
            'description' => $this->when($this->description, $this->description),
            'timeToComplete' => $this->when($this->isSurvey(),
                ceil($this->timeToComplete / 60)
            ),
            'url' => $this->getComputedLink(),
            'reward' => $this->when($reward, $reward),
            'required' => $this->when($targeting_params, $targeting_params),
            'callToAction' => $this->when($this->call_to_action, $this->call_to_action),
            'type' => $this->type,
            'incentive' => $this->when($this->incentive, $this->incentive),
            'targeting' => [
                $this->when((bool)$targeting, (object)$targeting)
                // targeting должен быть массивом объектов. В текущей реализации у нас только один объект будет
            ],
            'enableGrlInventory' => $this->when(isset($widget->enable_grl_inventory), $widget->enable_grl_inventory ?? 0)

        ];
    }


}
