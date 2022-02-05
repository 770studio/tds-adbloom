<?php

namespace App\Models;


use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * App\Models\ConversionsHourlyStat
 *
 * @method static Builder outdated()
 * @property int $id
 * @property string|null $Offer_name
 * @property string|null $Affiliate_company
 * @property string|null $OfferUrl_name
 * @property int $Stat_affiliate_id
 * @property int $Stat_offer_id
 * @property int $Stat_goal_id
 * @property int $Stat_offer_url_id
 * @property int $Stat_clicks
 * @property int $Stat_conversions
 * @property string $Stat_payout
 * @property string $Stat_revenue
 * @property string $Stat_profit
 * @property string|null $Goal_name
 * @property string $Stat_date
 * @property int $Stat_hour
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $StatDateTime
 * @method static \Illuminate\Database\Eloquent\Builder|ConversionsHourlyStat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConversionsHourlyStat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConversionsHourlyStat query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConversionsHourlyStat whereAffiliateCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversionsHourlyStat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversionsHourlyStat whereGoalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversionsHourlyStat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversionsHourlyStat whereOfferName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversionsHourlyStat whereOfferUrlName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversionsHourlyStat whereStatAffiliateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversionsHourlyStat whereStatClicks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversionsHourlyStat whereStatConversions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversionsHourlyStat whereStatDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversionsHourlyStat whereStatDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversionsHourlyStat whereStatGoalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversionsHourlyStat whereStatHour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversionsHourlyStat whereStatOfferId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversionsHourlyStat whereStatOfferUrlId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversionsHourlyStat whereStatPayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversionsHourlyStat whereStatProfit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversionsHourlyStat whereStatRevenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversionsHourlyStat whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ConversionsHourlyStat extends Model
{
    use HasFactory;

    public const TUNE_FIELDS = [
        "Offer.name", "Affiliate.company", "OfferUrl.name", "Stat.affiliate_id",
        "Stat.offer_id", "Stat.goal_id", "Stat.offer_url_id", "Stat.clicks",
        "Stat.conversions", "Stat.payout", "Stat.revenue", "Stat.profit",
        "Goal.name", "Stat.date", "Stat.hour"
    ];
    //const ID_FIELD = ;
    protected $guarded = [];

    /*  public static function getFields()
      {
          return collect(Schema::getColumnListing('conversions_hourly_stats'))
              ->filter(function ($value) {
                  return $value != "id";
              })
              ->toArray();
          return (new static)->getFillable();
      }*/

    public static function dateHourExists(Carbon $stat_date, int $stat_hour)
    {
        return (new self)->where([
            ['Stat_date', $stat_date->toDateString()],
            ['Stat_hour', $stat_hour],
        ])->exists();
    }

    public static function scopeOutdated($q)
    {
        return $q->where('Stat_date', '<', now()->subMonths(3)->toDateString());
    }




}
