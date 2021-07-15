<?php

namespace App\Models;

use App\Jobs\doPartnerPostBack;
use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{
    //const UPDATE_STARTING_FROM_LAST_X_MONTHS =  0.01;
    const FIELDS = ["Stat.tune_event_id", "Affiliate.company", "Advertiser.company", "ConversionsMobile.adv_sub2", "ConversionsMobile.adv_sub3", "ConversionsMobile.adv_sub4", "ConversionsMobile.adv_sub5", "ConversionsMobile.affiliate_click_id", "ConversionsMobile.affiliate_unique1", "ConversionsMobile.affiliate_unique2", "ConversionsMobile.affiliate_unique3", "ConversionsMobile.affiliate_unique4", "ConversionsMobile.affiliate_unique5", "Goal.name", "Offer.name", "Stat.advertiser_id", "Stat.affiliate_id", "Stat.affiliate_info1", "Stat.affiliate_info2", "Stat.affiliate_info3", "Stat.affiliate_info4", "Stat.affiliate_info5", "Stat.currency", "Stat.goal_id", "Stat.payout", "Stat.revenue", "Stat.date", "Stat.datetime", "Stat.id", "Stat.offer_id", "Stat.source", "Stat.status"];
    const ID_FIELD = 'Stat.tune_event_id';
    protected $guarded = [];
    // protected $primaryKey = self::ID_FIELD;
    protected $casts = [
        'partner_postback_lastsent' => 'datetime',
    ];

    public function Partner()
    {
        return $this->belongsTo('App\Models\Partner', 'Stat_affiliate_id', 'external_id');
    }

    public function Opportunity()
    {
        return $this->belongsTo('App\Models\Opportunity', 'Stat_offer_id', 'external_id');
    }


    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        #TODO mb refactor to event
        static::created(function ($conversion) {
            if ($conversion->Partner && $conversion->Opportunity) {
                doPartnerPostBack::dispatch($conversion)
                    ->onQueue('postback_queue');

            }
        });
    }
}
