<?php

namespace App\Services\StatsAlerts;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class StatsAlertsInventoryService
{
    use DBDateRangeTrait;

    private Group $group;
    public string $timezone = 'EST';

    public function __construct(Group $group)
    {
        $this->group = $group;
    }


    public function ConversionResultPartnerIndependent($groupByHour = false, $zeroResultsOnly = false): Builder
    {
        //'stat_affiliate_id',  // all partners !!!

        $this->group->by(['partners' => false, 'hour' => $groupByHour]);

        $select = $groupBy = $this->group->get();
        $select[] = DB::raw('sum(Stat_conversions) as total_conversions');

        return DB::table('conversions_hourly_stats')
            ->select($select)
            ->groupBy($groupBy)
            ->when($zeroResultsOnly, function ($q) {
                return $q->having('total_conversions', 0);
            });

    }


}
