<?php

namespace App\Models\Integrations;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Integrations\DaliaOffers
 *
 * @property int $id
 * @property string $uuid
 * @property string|null $title
 * @property string|null $info_short
 * @property string|null $info
 * @property object|null $json
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|DaliaOffers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DaliaOffers newQuery()
 * @method static Builder|DaliaOffers onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|DaliaOffers query()
 * @method static \Illuminate\Database\Eloquent\Builder|DaliaOffers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DaliaOffers whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DaliaOffers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DaliaOffers whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DaliaOffers whereInfoShort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DaliaOffers whereJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DaliaOffers whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DaliaOffers whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DaliaOffers whereUuid($value)
 * @method static Builder|DaliaOffers withTrashed()
 * @method static Builder|DaliaOffers withoutTrashed()
 * @mixin Eloquent
 */
class DaliaOffers extends Model
{
    use HasFactory , SoftDeletes;


    protected $table = 'dalia_offers';
    protected $fillable = [ 'uuid', 'title', 'info_short', 'info', 'json'];
    protected $casts = [
        'json' => 'object',
    ];
}
