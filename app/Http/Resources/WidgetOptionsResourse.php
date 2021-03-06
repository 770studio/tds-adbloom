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
            "partnerId" => $this->partner->external_id,
            "partnerName" => $this->partnerName,
            "incentive" => $this->partner->isIncentive(),
            "themeConfig" => [
                "cta" => $this->cta,
                "logoUrl" => StoreImageHelper::getPublicImageResource($this->partner->logo),
                "showHead" => (bool)$this->showHead,
                "textColor" => $this->textColor,
                "buttonBackground" => $this->buttonBackground,
                "rewardBackground" => $this->rewardBackground,
                "buttonTextColor" => $this->buttonTextColor,
                "rewardTextColor" => $this->rewardTextColor,
                "inAppCurrencySymbol" => [
                    'variant' => $this->inAppCurrencySymbolUrl_type,
                    'value' => $this->inAppCurrencySymbolUrl_type === 'image'
                        ? StoreImageHelper::getPublicImageResource($this->inAppCurrencySymbolUrl)
                        : $this->inAppCurrencySymbolUrl_text
                ],
                "fonts" => [
                    "heading" => [
                        "fontFamily" => GoogleFont::getFont($this->headingFontFamily, 'Lato'),
                        "fontWeight" => $this->headingfontWeight
                    ],
                    "cta" => [
                        "fontFamily" => GoogleFont::getFont($this->ctaFontFamily, 'Roboto'),
                        "fontWeight" => $this->ctaFontWeight
                    ],
                    "body" => [
                        "fontFamily" => GoogleFont::getFont($this->bodyFontFamily, 'Roboto'),
                        "fontWeight" => $this->bodyFontWeight
                    ],
                ]
            ]


        ];
    }
}
