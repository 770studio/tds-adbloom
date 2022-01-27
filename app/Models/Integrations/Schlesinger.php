<?php

namespace App\Models\Integrations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schlesinger extends Model
{
    use HasFactory;

    protected $table = 'schlesinger_surveys';
    protected $guarded = [];
    protected $casts = [
        'UpdateTimeStamp' => 'datetime',
        'Qual_UpdateTimeStamp' => 'datetime',
        'Quota_UpdateTimeStamp' => 'datetime',
        'Group_UpdateTimeStamp' => 'datetime',
    ];


}
