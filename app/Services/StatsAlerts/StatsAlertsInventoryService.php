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


    private Group $group;

    public function __construct(Group $group)
    {
        $this->group = $group;
    }


    public function ConversionResultPartnerIndependent($groupByHour = false, $zeroResultsOnly = false): self
    {
        //'stat_affiliate_id',  // all partners !!!

        $this->group->by(['partners' => false, 'hour' => $groupByHour]);

        $select = $groupBy = $this->group->get();
        $select[] = DB::raw('sum(Stat_conversions) as total_conversions');

        $this->queryBuilder = DB::table('conversions_hourly_stats')
            ->select($select)
            ->groupBy($groupBy)
            ->when($zeroResultsOnly, function ($q) {
                return $q->having('total_conversions', 0);
            });

        return $this;

    }


}
