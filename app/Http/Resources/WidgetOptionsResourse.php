<?php

namespace App\Http\Resources;


use App\Helpers\StoreImageHelper;
use App\Models\Infrastructure\GoogleFont;
use App\Models\Widget;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class WidgetOptionsResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        /** @var Widget $this */
        return [
            "enableGrlInventory" => (bool)$this->enable_grl_inventory,
            "logoUrl" => StoreImageHelper::getPublicImageResource($this->partner->logo),
            "showHead" => (bool)$this->showHead,
            "partnerName" => $this->partnerName,
            "fontFamily" => GoogleFont::getLongName($this->fontFamily ?? GoogleFont::DEFAULT_FONT),
            "fontColor" => $this->fontColor,
            "fontSize" => $this->fontSize,
            "primaryColor" => $this->primaryColor,
            "secondaryColor" => $this->secondaryColor,
            "inAppCurrencySymbolUrl" => StoreImageHelper::getPublicImageResource($this->inAppCurrencySymbolUrl),
        ];
    }
}
