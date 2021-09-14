<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Widget extends BaseModel
{
    use HasFactory;

    protected $casts = [
        'platforms' => 'object',
        'tags' => 'object',
        'countries' => 'object',
    ];

    public function Partner()
    {
        return $this->belongsTo('App\Models\Partner');
    }


    public function opportunities()
    {
        return $this->belongsToMany(Opportunity::class, 'widget_opportunity', 'widget_id', 'opportunity_id');
    }
    public function isDynamic()
    {
        return $this->dynamic_or_static == 0;
    }
    public function isStatic()
    {
        return $this->dynamic_or_static == 1;
    }
}
