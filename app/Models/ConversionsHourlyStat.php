<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ConversionsHourlyStat extends Model
{
    use HasFactory;

    public const FIELDS = [
        "Offer.name", "Affiliate.company", "OfferUrl.name", "Stat.affiliate_id", "Stat.offer_id", "Stat.goal_id", "Stat.offer_url_id", "Stat.clicks", "Stat.conversions", "Stat.payout", "Stat.revenue", "Stat.profit", "Goal.name"
    ];
    //const ID_FIELD = ;
    protected $guarded = [];

    public static function getFields()
    {
        return collect(Schema::getColumnListing('conversions_hourly_stats'))
            ->filter(function ($value) {
                return $value != "id";
            })
            ->toArray();
        return (new static)->getFillable();
    }
}
