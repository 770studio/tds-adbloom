<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Opportunity extends BaseModel
{
    use HasFactory;


    const TYPES = ['offer'=>'offer', 'survey'=>'survey'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Client()
    {
        return $this->belongsTo('App\Models\Client');
    }
    public function conversions()
    {
        return $this->hasMany('App\Models\Conversion', 'Stat_offer_id', 'external_id');
    }

    public function widgets()
    {
        return $this->belongsToMany(Widget::class, 'widget_opportunity',  'opportunity_id', 'widget_id');
    }

    /**
     * Get all of the opportunity's tags.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

}
