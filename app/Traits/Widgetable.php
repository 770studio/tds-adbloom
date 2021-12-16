<?php

namespace App\Traits;

use App\Models\Partner;
use App\Models\Widget;
use Illuminate\Support\Facades\Log;

trait Widgetable
{
    private ?Partner $partner = null;
    private ?Widget $widget = null;

    public function getPartner(): ?Partner
    {
        return $this->partner;
    }

    public function setPartner(Partner $partner): self
    {
        $this->partner = $this->overridePartner() ?? $partner;
        return $this;
    }

    public function getWidget(): ?Widget
    {
        return $this->widget;
    }

    public function setWidget(Widget $widget): self
    {
        $this->widget = $widget;
        $this->setPartner($widget->partner);
        return $this;
    }


    private function overridePartner(): ?Partner
    {
        // partner is either in partnerId of the request or related to widget
        if (optional(request())->get('partnerId') && $partner = Partner::where('external_id', request()->partnerId)->first()) {
            Log::channel('queue')->debug('partner is overridden:' . $partner->external_id);
            return $partner;
        }
        return null;
    }
}
