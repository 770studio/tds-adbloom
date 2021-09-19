<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Opportunity extends BaseModel
{
    use HasFactory;

    protected $casts = [
        'targeting_params' => 'object',
        'platforms' => 'object',
        'genders' => 'object',
        'countries' => 'object',
    ];
    const TYPES = ['offer' => 'offer', 'survey' => 'survey'];

    /**
     * @return BelongsTo
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

    public function isSurvey()
    {
        return $this->type == self::TYPES['survey'];
    }

}
