<?php

namespace App\Models\Integrations\Schlesinger;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\SchlesingerSurveyQualificationQuestion
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $LanguageId
 * @property int $qualificationId
 * @property string $name
 * @property string $text
 * @property int $qualificationCategoryId
 * @property int $qualificationTypeId
 * @property int $qualificationCategoryGDPRId
 * @method static Builder|SchlesingerSurveyQualificationQuestion newModelQuery()
 * @method static Builder|SchlesingerSurveyQualificationQuestion newQuery()
 * @method static Builder|SchlesingerSurveyQualificationQuestion query()
 * @method static Builder|SchlesingerSurveyQualificationQuestion whereCreatedAt($value)
 * @method static Builder|SchlesingerSurveyQualificationQuestion whereId($value)
 * @method static Builder|SchlesingerSurveyQualificationQuestion whereLanguageId($value)
 * @method static Builder|SchlesingerSurveyQualificationQuestion whereName($value)
 * @method static Builder|SchlesingerSurveyQualificationQuestion whereQualificationCategoryGDPRId($value)
 * @method static Builder|SchlesingerSurveyQualificationQuestion whereQualificationCategoryId($value)
 * @method static Builder|SchlesingerSurveyQualificationQuestion whereQualificationId($value)
 * @method static Builder|SchlesingerSurveyQualificationQuestion whereQualificationTypeId($value)
 * @method static Builder|SchlesingerSurveyQualificationQuestion whereText($value)
 * @method static Builder|SchlesingerSurveyQualificationQuestion whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection|SchlesingerSurveyQualificationAnswer[] $answers
 * @property-read int|null $answers_count
 */
class SchlesingerSurveyQualificationQuestion extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function answers()
    {
        return $this->hasMany(SchlesingerSurveyQualificationAnswer::class, 'qualification_internalId', 'id');
    }



}
