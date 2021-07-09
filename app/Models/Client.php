<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends BaseModel
{
    use HasFactory;

    const STATUSES = ['active', 'pending', 'deleted', 'paused'];

    public function opportunities()
    {
        return $this->hasMany('App\Models\Opportunity');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
/*    public function getRouteKeyName()
    {
        return 'short_id';
    }*/
}
