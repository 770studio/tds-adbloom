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
            "partnerId" => $this->partner->short_id,
            "partnerName" => $this->partnerName,
            "cta" => $this->cta,
            "themeConfig" => [
                "logoUrl" => StoreImageHelper::getPublicImageResource($this->partner->logo),
                "showHead" => (bool)$this->showHead,
                "textColor" => $this->textColor,
                "primaryColor" => $this->primaryColor,
                "secondaryColor" => $this->secondaryColor,
                "inAppCurrencySymbolUrl" => StoreImageHelper::getPublicImageResource($this->inAppCurrencySymbolUrl),
                "fonts" => [
                    "heading" => [
                        "fontFamily" => GoogleFont::getFont($this->headingFontFamily),
                        "fontWeight" => $this->headingfontWeight
                    ],
                    "cta" => [
                        "fontFamily" => GoogleFont::getFont($this->ctaFontFamily),
                        "fontWeight" => $this->ctaFontWeight
                    ],
                    "body" => [
                        "fontFamily" => GoogleFont::getFont($this->bodyFontFamily),
                        "fontWeight" => $this->bodyFontWeight
                    ],
                ]
            ]


        ];
    }
}
