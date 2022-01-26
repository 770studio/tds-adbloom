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
     * @param $payout
     * @return float|int
     */
    public function calulateReward($payout)
    {
        $percentage = $this->percentage
            ? $this->percentage / 100
            : 1; // if percentage = 0 use $payout

        $reward = number_format($payout * $percentage, 2, '.', '');

        return $this->convert_to_points
            ? round($reward * $this->points_multiplier)
            : $reward;


    }


}
