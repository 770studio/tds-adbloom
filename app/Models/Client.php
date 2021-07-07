<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends BaseModel
{
    use HasFactory;

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
