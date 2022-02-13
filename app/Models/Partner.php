<?php

namespace App\Models;


use Database\Factories\PartnerFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;


/**
 * Class Partner
 *
 * @package App\Models
 * @property int $id
 * @property string $short_id
 * @property string $external_id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $pending_url
 * @property int $send_pending_postback
 * @property array|null $send_pending_status
 * @property int|null $pending_timeout
 * @property int $rev_share
 * @property int|null $percentage
 * @property int $convert_to_points
 * @property string|null $points_multiplier
 * @property string|null $points_name
 * @property string|null $points_logo
 * @property string|null $logo
 * @property-read Collection|Conversion[] $conversions
 * @property-read int|null $conversions_count
 * @property-read Collection|Tag[] $tags
 * @property-read int|null $tags_count
 * @property-read Collection|Widget[] $widgets
 * @property-read int|null $widgets_count
 * @method static PartnerFactory factory(...$parameters)
 * @method static Builder|BaseModelWithAutoGeneratedShortId findByShortId($short_id)
 * @method static Builder|Partner newModelQuery()
 * @method static Builder|Partner newQuery()
 * @method static Builder|Partner query()
 * @method static Builder|Partner whereConvertToPoints($value)
 * @method static Builder|Partner whereCreatedAt($value)
 * @method static Builder|Partner whereExternalId($value)
 * @method static Builder|Partner whereId($value)
 * @method static Builder|Partner whereLogo($value)
 * @method static Builder|Partner whereName($value)
 * @method static Builder|Partner wherePendingTimeout($value)
 * @method static Builder|Partner wherePendingUrl($value)
 * @method static Builder|Partner wherePercentage($value)
 * @method static Builder|Partner wherePointsLogo($value)
 * @method static Builder|Partner wherePointsMultiplier($value)
 * @method static Builder|Partner wherePointsName($value)
 * @method static Builder|Partner whereRevShare($value)
 * @method static Builder|Partner whereSendPendingPostback($value)
 * @method static Builder|Partner whereSendPendingStatus($value)
 * @method static Builder|Partner whereShortId($value)
 * @method static Builder|Partner whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Partner extends BaseModelWithAutoGeneratedShortId
{
    use HasFactory;


    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        // 'saved' => PartnerUpdatedEvent::class,
        //  'updated' => PartnerUpdatedEvent::class,

    ];

    protected $casts = [
        'send_pending_status' => 'array'
    ];

    public function conversions()
    {
        return $this->hasMany('App\Models\Conversion', 'Stat_affiliate_id', 'external_id');
    }

    public function widgets()
    {
        return $this->hasMany('App\Models\Widget');
    }

    /**
     * Get all of the partner's tags.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * @param float|int $payout
     * @param float|int $denom
     * @return float|int
     */
    public function calulateReward($payout, $denom = 1)
    {
        $percentage = $this->percentage
            ? $this->percentage / 100
            : 1; // if percentage = 0 use $payout


        $reward = number_format($payout * $percentage * $denom, 2, '.', '');
        return $this->convert_to_points
            ? round($reward * $this->points_multiplier)
            : $reward;


    }

    public function isIncentive(): bool
    {
        return (bool)$this->rev_share;
    }
}
