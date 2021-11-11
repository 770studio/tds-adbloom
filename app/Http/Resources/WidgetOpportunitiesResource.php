<?php

namespace App\Http\Resources;

use App\Helpers\StoreImageHelper;
use App\Models\Infrastructure\Gender;
use App\Models\Infrastructure\Platform;
use App\Models\Infrastructure\TargetingParams;
use App\Models\Partner;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class WidgetOpportunitiesResource extends JsonResource
{

    private Partner $partner;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        $this->partner = $request->partnerId
            ? Partner::getDefault()
            : $this->widgets->first()->partner;
        $image = StoreImageHelper::getCreativesCDNUrl($this->image);
        $reward = $this->partner->calulateReward($this->payout);
        $targeting_params = TargetingParams::collection()->only($this->targeting_params)->values();
        $ageFromTo = $this->getAgeFromTo();

        //TODO remove unnecessary `when`
        return [
            'id' => $this->short_id,
            'title' => $this->when($this->name, $this->name),
            //'img' => $this->image,
            'image' => $this->when($image, $image),
            'description' => $this->when($this->description,$this->description),
            'timeToComplete' => $this->when($this->isSurvey(), $this->timeToComplete),
            'url' => $this->when($this->link,$this->link),
            'reward' => $this->when($reward,$reward),
            'required' => $this->when($targeting_params, $targeting_params),
            'callToAction' => $this->when($this->call_to_action,$this->call_to_action),
            'incentive' => $this->when($this->incentive, $this->incentive),
            'targeting' => [
                (object)[
                    'platform' => $this->when($this->platforms, Platform::collection()->only($this->platforms)->values()),
                    'country' => $this->when($this->countries, $this->countries),
                    'gender' => $this->when($this->genders, Gender::collection()->only($this->genders)->values()),
                    'age' => $this->when($ageFromTo, $ageFromTo)
                ],
                // targeting должен быть массивом объектов. В текущей реализации у нас только один объект будет
            ],


        ];
    }
}
