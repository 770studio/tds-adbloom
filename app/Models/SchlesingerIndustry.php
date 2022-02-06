<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\SchlesingerIndustry
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $IndustryId
 * @property string $Description
 * @method static Builder|SchlesingerIndustry newModelQuery()
 * @method static Builder|SchlesingerIndustry newQuery()
 * @method static Builder|SchlesingerIndustry query()
 * @method static Builder|SchlesingerIndustry whereCreatedAt($value)
 * @method static Builder|SchlesingerIndustry whereDescription($value)
 * @method static Builder|SchlesingerIndustry whereId($value)
 * @method static Builder|SchlesingerIndustry whereIndustryId($value)
 * @method static Builder|SchlesingerIndustry whereUpdatedAt($value)
 * @mixin Eloquent
 */
class SchlesingerIndustry extends Model
{
    use HasFactory;
}
