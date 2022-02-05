<?php

namespace App\Models;

use App\Events\ConversionUpdatingEvent;
use App\Jobs\doPartnerPostBack;
use Database\Factories\ConversionFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Conversion
 *
 * @package App\Models
 * @property int $id
 * @property string $Stat_tune_event_id UNIQUE ID
 * @property string|null $Affiliate_company
 * @property string|null $Advertiser_company
 * @property string|null $ConversionsMobile_adv_sub2
 * @property string|null $ConversionsMobile_adv_sub3
 * @property string|null $ConversionsMobile_adv_sub4
 * @property string|null $ConversionsMobile_adv_sub5
 * @property string|null $ConversionsMobile_affiliate_click_id
 * @property string|null $ConversionsMobile_affiliate_unique1
 * @property string|null $ConversionsMobile_affiliate_unique2
 * @property string|null $ConversionsMobile_affiliate_unique3
 * @property string|null $ConversionsMobile_affiliate_unique4
 * @property string|null $ConversionsMobile_affiliate_unique5
 * @property string|null $Goal_name
 * @property string|null $Offer_name
 * @property string|null $Stat_advertiser_id
 * @property string|null $Stat_affiliate_id
 * @property string|null $Stat_affiliate_info1
 * @property string|null $Stat_affiliate_info2
 * @property string|null $Stat_affiliate_info3
 * @property string|null $Stat_affiliate_info4
 * @property string|null $Stat_affiliate_info5
 * @property string|null $Stat_currency
 * @property string|null $Stat_goal_id
 * @property string|null $Stat_payout
 * @property string|null $Stat_revenue
 * @property string|null $Stat_date
 * @property Carbon|null $Stat_datetime
 * @property string|null $Stat_id
 * @property string|null $Stat_offer_id
 * @property string|null $Stat_source
 * @property string|null $Stat_status
 * @property int $partner_postbacks
 * @property string|null $pending_sent
 * @property Carbon|null $partner_postback_lastsent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $user_payout
 * @property int $user_points
 * @property-read Opportunity|null $Opportunity
 * @property-read Partner|null $Partner
 * @method static ConversionFactory factory(...$parameters)
 * @method static Builder|Conversion newModelQuery()
 * @method static Builder|Conversion newQuery()
 * @method static Builder|Conversion query()
 * @method static Builder|Conversion whereAdvertiserCompany($value)
 * @method static Builder|Conversion whereAffiliateCompany($value)
 * @method static Builder|Conversion whereConversionsMobileAdvSub2($value)
 * @method static Builder|Conversion whereConversionsMobileAdvSub3($value)
 * @method static Builder|Conversion whereConversionsMobileAdvSub4($value)
 * @method static Builder|Conversion whereConversionsMobileAdvSub5($value)
 * @method static Builder|Conversion whereConversionsMobileAffiliateClickId($value)
 * @method static Builder|Conversion whereConversionsMobileAffiliateUnique1($value)
 * @method static Builder|Conversion whereConversionsMobileAffiliateUnique2($value)
 * @method static Builder|Conversion whereConversionsMobileAffiliateUnique3($value)
 * @method static Builder|Conversion whereConversionsMobileAffiliateUnique4($value)
 * @method static Builder|Conversion whereConversionsMobileAffiliateUnique5($value)
 * @method static Builder|Conversion whereCreatedAt($value)
 * @method static Builder|Conversion whereGoalName($value)
 * @method static Builder|Conversion whereId($value)
 * @method static Builder|Conversion whereOfferName($value)
 * @method static Builder|Conversion wherePartnerPostbackLastsent($value)
 * @method static Builder|Conversion wherePartnerPostbacks($value)
 * @method static Builder|Conversion wherePendingSent($value)
 * @method static Builder|Conversion whereStatAdvertiserId($value)
 * @method static Builder|Conversion whereStatAffiliateId($value)
 * @method static Builder|Conversion whereStatAffiliateInfo1($value)
 * @method static Builder|Conversion whereStatAffiliateInfo2($value)
 * @method static Builder|Conversion whereStatAffiliateInfo3($value)
 * @method static Builder|Conversion whereStatAffiliateInfo4($value)
 * @method static Builder|Conversion whereStatAffiliateInfo5($value)
 * @method static Builder|Conversion whereStatCurrency($value)
 * @method static Builder|Conversion whereStatDate($value)
 * @method static Builder|Conversion whereStatDatetime($value)
 * @method static Builder|Conversion whereStatGoalId($value)
 * @method static Builder|Conversion whereStatId($value)
 * @method static Builder|Conversion whereStatOfferId($value)
 * @method static Builder|Conversion whereStatPayout($value)
 * @method static Builder|Conversion whereStatRevenue($value)
 * @method static Builder|Conversion whereStatSource($value)
 * @method static Builder|Conversion whereStatStatus($value)
 * @method static Builder|Conversion whereStatTuneEventId($value)
 * @method static Builder|Conversion whereUpdatedAt($value)
 * @method static Builder|Conversion whereUserPayout($value)
 * @method static Builder|Conversion whereUserPoints($value)
 * @mixin Eloquent
 */
class Conversion extends Model
{
    use HasFactory;

    //const UPDATE_STARTING_FROM_LAST_X_MONTHS =  0.01;
    public const TUNE_FIELDS = ["Stat.tune_event_id", "Affiliate.company", "Advertiser.company", "ConversionsMobile.adv_sub2", "ConversionsMobile.adv_sub3", "ConversionsMobile.adv_sub4", "ConversionsMobile.adv_sub5", "ConversionsMobile.affiliate_click_id", "ConversionsMobile.affiliate_unique1", "ConversionsMobile.affiliate_unique2", "ConversionsMobile.affiliate_unique3", "ConversionsMobile.affiliate_unique4", "ConversionsMobile.affiliate_unique5", "Goal.name", "Offer.name", "Stat.advertiser_id", "Stat.affiliate_id", "Stat.affiliate_info1", "Stat.affiliate_info2", "Stat.affiliate_info3", "Stat.affiliate_info4", "Stat.affiliate_info5", "Stat.currency", "Stat.goal_id", "Stat.payout", "Stat.revenue", "Stat.date", "Stat.datetime", "Stat.id", "Stat.offer_id", "Stat.source", "Stat.status"];
    public const ID_FIELD = 'Stat.tune_event_id';
    protected $guarded = [];
    // protected $primaryKey = self::ID_FIELD;
    protected $casts = [
        'partner_postback_lastsent' => 'datetime',
        'Stat_datetime' => 'datetime',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
         'creating' => ConversionUpdatingEvent::class,
          'updating' => ConversionUpdatingEvent::class,

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
