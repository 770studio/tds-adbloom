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
}
