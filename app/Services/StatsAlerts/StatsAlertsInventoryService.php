<?php

namespace App\Services\StatsAlerts;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @var Builder $queryBuilder
 */
final class StatsAlertsInventoryService
{


    private StatsGroupBy $groupBy;
    /**
     * @var mixed|string
     */
    private $timezone;

    public function __construct(StatsGroupBy $groupBy) // $timezone = "EST"
    {
        $this->groupBy = $groupBy;

    }


    /**
     *  get click through  grouped by offer_id
     *
     */
    public function getConversionClicksCRValue(FlexPeriod $period, callable $whereMore = null): Collection
    {
        return DB::table('conversions_hourly_stats')
            ->select('Stat_offer_id', 'OfferUrl_name', 'Offer_name',
                DB::raw('sum( Stat_clicks ) AS clicks'),
                DB::raw('sum( Stat_conversions ) AS conversions'),
                DB::raw('IF
                    ( sum( Stat_conversions ) = 0 OR sum(Stat_clicks ) = 0, 0,
                    ROUND(sum( Stat_conversions )*100 / sum( Stat_clicks ),2) ) AS cr_value  ')
            )->groupBy(
                $this->groupBy->Offer()->toArray()
            )
            ->where(
                $period->toArray()
            )->when($whereMore, function (Builder $query, callable $more) {
                return $more($query);
            })->get();


    }

    public function getConversionClicksCRValueWithNoActivity(FlexPeriod $period, $offers = []): Collection
    {
        return $this->getConversionClicksCRValue($period, function (Builder $query) use ($offers) {
            return $query->havingRaw('conversions = 0 ')
                ->when($offers, function (Builder $query, $offers) {
                    return $query->whereIn('Stat_offer_id', $offers);
                });
        });
    }


}
