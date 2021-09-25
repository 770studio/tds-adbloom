<?php

namespace App\Http\Resources;

use App\Helpers\StoreImageHelper;
use App\Models\Infrastructure\Country;
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
        $this->partner = $this->widgets->first()->partner;

        return [
            'id' => $this->short_id,
            'title' => $this->name,
            //'img' => $this->image,
            'image' => StoreImageHelper::getCreativesCDNUrl($this->image),
            'description' => $this->description,
            'timeToComplete' => $this->when($this->isSurvey(), $this->timeToComplete),
            'url' => $this->link,
            'reward' => $this->partner->calulateReward($this->payout),
            'required' => TargetingParams::collection()->only($this->targeting_params)->values(),
            'callToAction' => $this->call_to_action,
            'incentive' => $this->incentive,
            'targeting' => [
                'platform' => $this->when($this->platforms, Platform::collection()->only($this->platforms)->values()),
                'country' => $this->when($this->countries, Country::collection()->only($this->countries)->values()),
                'gender' => $this->when($this->genders, Gender::collection()->only($this->genders)->values()),
                'age' => [
                    'from' => $this->age_from,
                    'to' => $this->age_to,
                ]
            ],


        ];
    }
}
