<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\SchlesingerSurvey
 *
 * @property int $id
 * @property int $SurveyId
 * @property int $LanguageId
 * @property int|null $BillingEntityId
 * @property string $CPI
 * @property int $LOI
 * @property string $IR
 * @property int $IndustryId
 * @property int $StudyTypeId
 * @property int $IsMobileAllowed
 * @property int $IsNonMobileAllowed
 * @property int $IsTabletAllowed
 * @property int $IsSurveyGroupExist
 * @property int $CollectPII
 * @property int $AccountId
 * @property int $UrlTypeId
 * @property string $UpdateTimeStamp
 * @property int $IsManualInc
 * @property int $IsQuotaLevelCPI
 * @property string $LiveLink
 * @property string $Qual_UpdateTimeStamp
 * @property string $Quota_UpdateTimeStamp
 * @property string $Group_UpdateTimeStamp
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|SchlesingerSurvey newModelQuery()
 * @method static Builder|SchlesingerSurvey newQuery()
 * @method static Builder|SchlesingerSurvey query()
 * @method static Builder|SchlesingerSurvey whereAccountId($value)
 * @method static Builder|SchlesingerSurvey whereBillingEntityId($value)
 * @method static Builder|SchlesingerSurvey whereCPI($value)
 * @method static Builder|SchlesingerSurvey whereCollectPII($value)
 * @method static Builder|SchlesingerSurvey whereCreatedAt($value)
 * @method static Builder|SchlesingerSurvey whereGroupUpdateTimeStamp($value)
 * @method static Builder|SchlesingerSurvey whereIR($value)
 * @method static Builder|SchlesingerSurvey whereId($value)
 * @method static Builder|SchlesingerSurvey whereIndustryId($value)
 * @method static Builder|SchlesingerSurvey whereIsManualInc($value)
 * @method static Builder|SchlesingerSurvey whereIsMobileAllowed($value)
 * @method static Builder|SchlesingerSurvey whereIsNonMobileAllowed($value)
 * @method static Builder|SchlesingerSurvey whereIsQuotaLevelCPI($value)
 * @method static Builder|SchlesingerSurvey whereIsSurveyGroupExist($value)
 * @method static Builder|SchlesingerSurvey whereIsTabletAllowed($value)
 * @method static Builder|SchlesingerSurvey whereLOI($value)
 * @method static Builder|SchlesingerSurvey whereLanguageId($value)
 * @method static Builder|SchlesingerSurvey whereLiveLink($value)
 * @method static Builder|SchlesingerSurvey whereQualUpdateTimeStamp($value)
 * @method static Builder|SchlesingerSurvey whereQuotaUpdateTimeStamp($value)
 * @method static Builder|SchlesingerSurvey whereStudyTypeId($value)
 * @method static Builder|SchlesingerSurvey whereSurveyId($value)
 * @method static Builder|SchlesingerSurvey whereUpdateTimeStamp($value)
 * @method static Builder|SchlesingerSurvey whereUpdatedAt($value)
 * @method static Builder|SchlesingerSurvey whereUrlTypeId($value)
 * @mixin Eloquent
 */
class SchlesingerSurvey extends Model
{
    use HasFactory;
}
