<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends BaseModel
{
    use HasFactory;

    const STATUSES = [
        'Active' => 'active',
        'Pending' => 'pending',
        'Deleted' => 'deleted',
        'Paused' => 'paused'
    ];

    public function opportunities()
    {
        return $this->hasMany('App\Models\Opportunity');
    }

    public function redirectStatuses()
    {
        return RedirectStatus::all();
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
