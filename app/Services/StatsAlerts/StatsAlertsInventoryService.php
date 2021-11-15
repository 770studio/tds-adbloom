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
     */
    public function getConversionClicksCRValue(Period24h $period): Collection
    {
        return DB::table('conversions_hourly_stats')
            ->select('Stat_offer_id', 'OfferUrl_name', 'Offer_name',
                DB::raw('sum( Stat_clicks ) AS clicks'),
                DB::raw('sum( Stat_conversions ) AS conversions'),
                DB::raw('IF
                    ( sum( Stat_conversions ) = 0 OR sum(Stat_clicks ) = 0, 0,
                    ROUND(sum( Stat_conversions )*100 / sum( Stat_clicks ),2) ) AS cr_value  ')
            )
            //->havingRaw('(sum( Stat_conversions ) > 0  OR sum(Stat_clicks ) > 0) ')  //do not consider 0 clicks + 0
            //->havingRaw('sum(Stat_clicks ) > ? ', [$min_clicks])  // ignore anything that is under xx clicks
            // + 0
            ->groupBy(
                $this->groupBy->Offer()->toArray()
            )
            ->where(
                $period->toArray()
            )
            ->get();


    }




}
