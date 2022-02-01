<?php

namespace App\Http\Resources;

use App\Helpers\ArrayHelper;
use App\Helpers\StoreImageHelper;
use App\Models\Infrastructure\Gender;
use App\Models\Infrastructure\Platform;
use App\Models\Infrastructure\TargetingParams;
use App\Models\Opportunity;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class OpportunitiesResource extends JsonResource
{


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


        $image = StoreImageHelper::getCreativesCDNUrl($this->image);

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
            'url' => $this->computed_link,
            'reward' => $this->when($this->isRewarding(), $this->reward),
            'required' => $this->when($targeting_params->isNotEmpty(), $targeting_params),
            'callToAction' => $this->when($this->call_to_action, $this->call_to_action),
            'type' => $this->type,
            'incentive' => $this->incentive,
            'incentiveCallToAction' => $this->when($this->incentive_call_to_action, $this->incentive_call_to_action),
            'targeting' => [
                $this->when((bool)$targeting, (object)$targeting)
                // targeting должен быть массивом объектов. В текущей реализации у нас только один объект будет
            ],

        ];
    }


}
