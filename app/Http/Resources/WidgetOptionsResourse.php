<?php

namespace App\Http\Resources;


use App\Helpers\GoogleFontsHelper;
use App\Helpers\StoreImageHelper;
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
            "logoUrl" => StoreImageHelper::getPartnerLogo($this->partner),
            "showHead" => (bool)$this->showHead,
            "partnerName" => $this->partnerName,
            "fontFamily" => GoogleFontsHelper::getLongName($this->fontFamily ?? 'Work Sans'),
            "fontColor" => $this->fontColor,
            "fontSize" => $this->fontSize,
            "primaryColor" => $this->primaryColor,
            "secondaryColor" => $this->secondaryColor,
            //"inAppCurrencySymbolUrl" =>  $widget->inAppCurrencySymbolUrl  ,
        ];
    }
}
