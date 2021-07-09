<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Opportunity extends BaseModel
{
    use HasFactory;

    const TYPES = ['offer', 'survey'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Client()
    {
        return $this->belongsTo('App\Models\Client');
    }

}
