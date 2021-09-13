<?php

namespace App\Http\Resources;

use App\Helpers\StoreImageHelper;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class WidgetOpportunitiesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->short_id,
            'title' => $this->name,
            //'img' => $this->image,
            'image' => StoreImageHelper::getCreativesCDNUrl($this->image),
            'description' => $this->description,
            'timeToComplete' => $this->when($this->isSurvey(), $this->timeToComplete),
            'url' => $this->link,


        ];
    }
}
