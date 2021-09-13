<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WidgetOpportunitiesCollection;
use App\Models\Widget;

class WidgetController extends Controller
{
    public function opportunities($widget_short_id)
    {
        $widget = Widget::where('short_id', $widget_short_id)->firstOrFail();

        return new WidgetOpportunitiesCollection(
            $widget->opportunities
        );
    }
}
