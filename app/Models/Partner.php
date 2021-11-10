<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;


/**
 * Class Partner
 * @package App\Models
 *
 */
class Partner extends BaseModelWithAutoGeneratedShortId
{
    use HasFactory;

    // Это нужно для случаев, когда виджет могут использоавть нксколько партнёров, а настройка оферов должна быть общей.
    private static Partner $default;

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

    public static function setDefault($partnerExtId)
    {
        self::$default = self::where('external_id', $partnerExtId)->first();
    }

    public static function getDefault()
    {
        return self::$default;
    }

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

    public function calulateReward($payout): int
    {

        $reward = (int)number_format($payout * $this->percentage * 100, 0, '', '');

        return $this->convert_to_points
            ? $reward * $this->points_multiplier
            : $reward;


    }


}
