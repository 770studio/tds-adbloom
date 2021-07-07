<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedirectStatus extends Model
{
    use HasFactory;

    public function getRouteKeyName()
    {
        return 'code';
    }
}
