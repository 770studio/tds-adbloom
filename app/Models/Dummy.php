<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Dummy
 *
 * @method static Builder|Dummy newModelQuery()
 * @method static Builder|Dummy newQuery()
 * @method static Builder|Dummy query()
 * @mixin Eloquent
 */
class Dummy extends Model
{
    use HasFactory;
}
