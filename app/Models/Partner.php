<?php

namespace App\Models;


use App\Events\PartnerUpdatedEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;


/**
 * Class Partner
 * @package App\Models
 *
 */
class Partner extends BaseModel
{
    use HasFactory;

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => PartnerUpdatedEvent::class,
        //  'updated' => PartnerUpdatedEvent::class,
    ];

    protected $casts = [
        'send_pending_status' => 'array'
    ];

    public function conversions()
    {
        return $this->hasMany('App\Models\Conversion', 'Stat_affiliate_id', 'external_id');
    }

    /**
     * Get all of the partner's tags.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }


}
