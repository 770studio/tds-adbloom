<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\SchlesingerSurveyQualificationAnswer
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $qualification_internalId
 * @property int $answerId
 * @property string $text
 * @property string $answerCode
 * @method static Builder|SchlesingerSurveyQualificationAnswer newModelQuery()
 * @method static Builder|SchlesingerSurveyQualificationAnswer newQuery()
 * @method static Builder|SchlesingerSurveyQualificationAnswer query()
 * @method static Builder|SchlesingerSurveyQualificationAnswer whereAnswerCode($value)
 * @method static Builder|SchlesingerSurveyQualificationAnswer whereAnswerId($value)
 * @method static Builder|SchlesingerSurveyQualificationAnswer whereCreatedAt($value)
 * @method static Builder|SchlesingerSurveyQualificationAnswer whereId($value)
 * @method static Builder|SchlesingerSurveyQualificationAnswer whereQualificationInternalId($value)
 * @method static Builder|SchlesingerSurveyQualificationAnswer whereText($value)
 * @method static Builder|SchlesingerSurveyQualificationAnswer whereUpdatedAt($value)
 * @mixin Eloquent
 */
class SchlesingerSurveyQualificationAnswer extends Model
{
    use HasFactory;

    protected $guarded = [];
}
