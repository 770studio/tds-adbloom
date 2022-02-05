<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Tag
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Opportunity[] $opportunities
 * @property-read int|null $opportunities_count
 * @property-read Collection|Partner[] $partners
 * @property-read int|null $partners_count
 * @method static Builder|Tag newModelQuery()
 * @method static Builder|Tag newQuery()
 * @method static Builder|Tag query()
 * @method static Builder|Tag whereCreatedAt($value)
 * @method static Builder|Tag whereId($value)
 * @method static Builder|Tag whereName($value)
 * @method static Builder|Tag whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Tag extends Model
{
    use HasFactory;

    /**
     * Get all of the opportunities that are assigned this tag.
     */

    public function opportunities()
    {
        return $this->morphedByMany(Opportunity::class, 'taggable');
    }

    /**
     * Get all of the partners that are assigned this tag.
     */

    public function partners()
    {
        return $this->morphedByMany(Partner::class, 'taggable');
    }
}
