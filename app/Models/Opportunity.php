<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opportunity extends BaseModel
{
    use HasFactory;

    public function Client()
    {
        return $this->belongsTo('App\Models\Client');
    }

}
