<?php

namespace App\Services\StatsAlerts;

use App\Services\StatsAlerts\Traits\DBQueryWhereClauseExtendTrait;
use App\Services\StatsAlerts\Traits\StatMatchTrait;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * @var Builder $queryBuilder
 */
final class StatsAlertsInventoryService
{
    use DBQueryWhereClauseExtendTrait;


    private StatsGroupBy $groupBy;

    public function __construct(StatsGroupBy $groupBy)
    {
        $this->groupBy = $groupBy;
    }



    public function ConversionResultPartnerIndependent($groupByHour = false, $zeroResultsOnly = false): self
    {
        //'stat_affiliate_id',  // all partners !!!
        // select is almost same as groupby
        $select = $groupBy = $this->groupBy
            ->createFromArray(['partners' => false, 'hour' => $groupByHour])
            ->toArray();

        $select[] = DB::raw('sum(Stat_conversions) as total_conversions');

        $this->queryBuilder = DB::table('conversions_hourly_stats')
            ->select($select)
            ->groupBy($groupBy)
            ->when($zeroResultsOnly, function ($q) {
                return $q->having('total_conversions', 0);
            });

        return $this;

    }

    /**
     *  get click through  grouped by offer_id
     */
    public function ConversionClicks(): Builder
    {

        return DB::table('conversions_hourly_stats')
            ->select(
                'Stat_offer_id',
                DB::raw('IF
                    ( sum( Stat_conversions ) = 0 OR sum(Stat_clicks ) = 0, 0,
                    sum( Stat_clicks ) / sum( Stat_conversions ) ) AS clickthrough  ')
            )
            ->havingRaw('(sum( Stat_conversions ) > 0  OR sum(Stat_clicks ) > 0) ')  //do not consider 0 clicks + 0
            ->groupBy(
                $this->groupBy->Offer()->toArray()
            );


    }
}
