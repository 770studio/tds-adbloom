<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Yoursurveys extends Model
{
    use HasFactory;

    protected $table = 'yoursurveys_readme_io';
    protected $fillable = ['project_id', 'json'];
    protected $casts = [
        'json' => 'object',
    ];
}
