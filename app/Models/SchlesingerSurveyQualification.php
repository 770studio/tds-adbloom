<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\SchlesingerSurveyQualification
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $survey_internalId
 * @property int $qualification_internalId
 * @property string $UpdateTimeStamp
 * @property mixed $AnswerIds
 * @property mixed $AnswerCodes
 * @method static Builder|SchlesingerSurveyQualification newModelQuery()
 * @method static Builder|SchlesingerSurveyQualification newQuery()
 * @method static Builder|SchlesingerSurveyQualification query()
 * @method static Builder|SchlesingerSurveyQualification whereAnswerCodes($value)
 * @method static Builder|SchlesingerSurveyQualification whereAnswerIds($value)
 * @method static Builder|SchlesingerSurveyQualification whereCreatedAt($value)
 * @method static Builder|SchlesingerSurveyQualification whereId($value)
 * @method static Builder|SchlesingerSurveyQualification whereQualificationInternalId($value)
 * @method static Builder|SchlesingerSurveyQualification whereSurveyInternalId($value)
 * @method static Builder|SchlesingerSurveyQualification whereUpdateTimeStamp($value)
 * @method static Builder|SchlesingerSurveyQualification whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $SurveyId
 * @property int $qualificationId
 * @method static Builder|SchlesingerSurveyQualification whereQualificationId($value)
 * @method static Builder|SchlesingerSurveyQualification whereSurveyId($value)
 */
class SchlesingerSurveyQualification extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'AnswerIds' => 'array',
        'AnswerCodes' => 'array'
    ];
}
