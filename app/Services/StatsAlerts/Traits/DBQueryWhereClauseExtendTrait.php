<?php

namespace App\Services\StatsAlerts\Traits;

use Carbon\CarbonImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

trait DBQueryWhereClauseExtendTrait
{
    private Builder $queryBuilder;

    public function getQueryBuilder(): Builder
    {
        return $this->queryBuilder;
    }

    public function forTheLastHour(): self
    {
        $date = $this->getNewMutableNowInst();
        $hour = $date->subHour()->hour;
        dump((string)$date, $hour);
        $this->queryBuilder->where('Stat_date', $date->toDateString())
            ->where('Stat_hour', $hour);
        return $this;

    }

    public function getNewMutableNowInst(): Carbon
    {
        return Carbon::now()->timezone(config('services.tune_api.stats_timezone'));
    }

    public function forTheHourBeforeLastHour(): self
    {
        $date = $this->getNewMutableNowInst();
        $hour = $date->subHours(2)->hour;
        $this->queryBuilder->where('Stat_date', $date->toDateString())
            ->where('Stat_hour', $hour);
        return $this;
    }

    public function forTheLast24Hours(): self
    {
        $dateStart = $this->getNewMutableNowInst()->subHours(25);

        $this->queryBuilder->where([
            ['Stat_date', '>=', $dateStart->toDateString()],
            ['Stat_hour', '>', $dateStart->hour]
        ]);
        return $this;

    }

    /**
     *  previous 24 hours = hours starting from subHours(49) , ending on  subHours(25)
     */
    public function forThePrev24Hours(): self
    {
        $dateStart = $this->getNewMutableNowInst()->subHours(49);
        $dateEnd = $this->getNewMutableNowInst()->subHours(25);

        $this->queryBuilder->where([
            ['StatDateTime', '>=', $dateStart->toDateTimeString()],
            ['StatDateTime', '<', $dateEnd->toDateTimeString()]
        ]);
        return $this;

    }

    public function foraCustomDateHour(CarbonImmutable $date, int $hour): self
    {
        $this->queryBuilder->where('Stat_date', $date->toDateString())
            ->where('Stat_hour', $hour);
        return $this;

    }

    public function matchCandidate(object $candidate, $omitPartner = false): self
    {
        $this->queryBuilder->where(
            [
                ['Stat_offer_id', $candidate->Stat_offer_id],
                ['Stat_offer_url_id', $candidate->Stat_offer_url_id],
                ['Stat_goal_id', $candidate->Stat_goal_id]
            ]
        )->when(!$omitPartner, function ($q) use ($candidate) {
            return $q->where('Stat_affiliate_id', $candidate->Stat_affiliate_id);
        });

        return $this;

    }

}
