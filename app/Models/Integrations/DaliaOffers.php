<?php

namespace App\Models\Integrations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DaliaOffers extends Model
{
    use HasFactory , SoftDeletes;


    protected $table = 'dalia_offers';
    protected $fillable = [ 'uuid', 'title', 'info_short', 'info', 'json'];
    protected $casts = [
        'json' => 'object',
    ];
}
