<?php

namespace App\Models\Integrations;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Integrations\Yoursurveys
 *
 * @property int $id
 * @property int $project_id
 * @property object $json
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Yoursurveys newModelQuery()
 * @method static Builder|Yoursurveys newQuery()
 * @method static Builder|Yoursurveys query()
 * @method static Builder|Yoursurveys whereCreatedAt($value)
 * @method static Builder|Yoursurveys whereId($value)
 * @method static Builder|Yoursurveys whereJson($value)
 * @method static Builder|Yoursurveys whereProjectId($value)
 * @method static Builder|Yoursurveys whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Yoursurveys extends Model
{
    use HasFactory;

    protected $table = 'yoursurveys_readme_io';
    protected $fillable = ['project_id', 'json'];
    protected $casts = [
        'json' => 'object',
    ];
}
