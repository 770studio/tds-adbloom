<?php

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @method static Builder outdated()
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
