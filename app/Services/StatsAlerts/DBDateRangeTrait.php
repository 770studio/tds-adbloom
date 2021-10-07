<?php

namespace App\Services\StatsAlerts;

use Carbon\CarbonImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

trait DBDateRangeTrait
{
    public string $timezone = 'EST';

    public function getNow()
    {
        return Carbon::now()->timezone($this->timezone);
    }

    public function forTheLastHour(Builder $query)
    {
        $date = $this->getNow();
        $hour = $date->subHour()->hour;
        return $query->where('Stat_date', $date->toDateString())
            ->where('Stat_hour', $hour);
    }

    public function forTheHourBeforeLastHour(Builder $query)
    {
        $date = $this->getNow();
        $hour = $date->subHours(2)->hour;
        return $query->where('Stat_date', $date->toDateString())
            ->where('Stat_hour', $hour);
    }

    public function forTheLast24Hours(Builder $query)
    {
        $dateStart = $this->getNow()->subHours(25);
        $dateEnd = $this->getNow();

        return $query->where([
            ['Stat_date', '>=', $dateStart->toDateString()],
            ['Stat_hour', '>', $dateStart->hour],
            ['Stat_date', '<=', $dateEnd->toDateString()],
            ['Stat_hour', '<', $dateEnd->hour]
        ]);
    }

    public function foraCustomDateHour(Builder $query, CarbonImmutable $date, int $hour)
    {
        return $query->where('Stat_date', $date->toDateString())
            ->where('Stat_hour', $hour);
    }
}
