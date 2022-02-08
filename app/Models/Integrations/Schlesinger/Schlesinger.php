<?php

namespace App\Models\Integrations\Schlesinger;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Integrations\Schlesinger
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
 * @property Carbon $UpdateTimeStamp
 * @property int $IsManualInc
 * @property int $IsQuotaLevelCPI
 * @property string $LiveLink
 * @property Carbon $Qual_UpdateTimeStamp
 * @property Carbon $Quota_UpdateTimeStamp
 * @property Carbon $Group_UpdateTimeStamp
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Schlesinger newModelQuery()
 * @method static Builder|Schlesinger newQuery()
 * @method static Builder|Schlesinger query()
 * @method static Builder|Schlesinger whereAccountId($value)
 * @method static Builder|Schlesinger whereBillingEntityId($value)
 * @method static Builder|Schlesinger whereCPI($value)
 * @method static Builder|Schlesinger whereCollectPII($value)
 * @method static Builder|Schlesinger whereCreatedAt($value)
 * @method static Builder|Schlesinger whereGroupUpdateTimeStamp($value)
 * @method static Builder|Schlesinger whereIR($value)
 * @method static Builder|Schlesinger whereId($value)
 * @method static Builder|Schlesinger whereIndustryId($value)
 * @method static Builder|Schlesinger whereIsManualInc($value)
 * @method static Builder|Schlesinger whereIsMobileAllowed($value)
 * @method static Builder|Schlesinger whereIsNonMobileAllowed($value)
 * @method static Builder|Schlesinger whereIsQuotaLevelCPI($value)
 * @method static Builder|Schlesinger whereIsSurveyGroupExist($value)
 * @method static Builder|Schlesinger whereIsTabletAllowed($value)
 * @method static Builder|Schlesinger whereLOI($value)
 * @method static Builder|Schlesinger whereLanguageId($value)
 * @method static Builder|Schlesinger whereLiveLink($value)
 * @method static Builder|Schlesinger whereQualUpdateTimeStamp($value)
 * @method static Builder|Schlesinger whereQuotaUpdateTimeStamp($value)
 * @method static Builder|Schlesinger whereStudyTypeId($value)
 * @method static Builder|Schlesinger whereSurveyId($value)
 * @method static Builder|Schlesinger whereUpdateTimeStamp($value)
 * @method static Builder|Schlesinger whereUpdatedAt($value)
 * @method static Builder|Schlesinger whereUrlTypeId($value)
 * @mixin Eloquent
 */
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

    public function qualifications(): HasMany
    {
        return $this->hasMany(SchlesingerSurveyQualification::class, 'survey_internalId', 'id');
    }

}
