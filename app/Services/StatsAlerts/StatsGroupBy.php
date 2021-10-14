<?php

namespace App\Services\StatsAlerts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;


class StatsGroupBy implements Arrayable
{
    private array $StatsGroupByOptions =
        [
            "Stat_affiliate_id",
            "Stat_offer_id",
            "Stat_offer_url_id",
            "Stat_goal_id",
            "Stat_date",
            "Stat_hour"
        ];

    private array $StatsGroupBy = [];

    public function __construct()
    {
        $this->reset();
    }

    public function toArray(): array
    {
        return $this->StatsGroupBy;
    }

    public function collection(): Collection
    {
        return collect($this->StatsGroupBy);
    }

    public function noPartner(): self
    {
        unset($this->StatsGroupBy[array_search("Stat_affiliate_id", $this->StatsGroupBy, true)]);
        return $this;
    }

    public function noHour(): self
    {
        unset($this->StatsGroupBy[array_search("Stat_hour", $this->StatsGroupBy, true)]);
        return $this;
    }

    public function reset(): self
    {
        $this->StatsGroupBy = $this->StatsGroupByOptions;
        return $this;
    }

    public function Offer(): self
    {
        $this->StatsGroupBy = ["Stat_offer_id"];
        return $this;

    }

    public function createFromArray(array $params): self
    {
        $this->reset();
        foreach ($params as $criteria => $value) {
            switch ($criteria) {
                case 'partners' :
                    if (!$value) {
                        $this->noPartner();
                    }
                    break;
                case 'hour' :
                    if(!$value) {
                        $this->noHour();
                    }
                    break;

            }
        }
        return $this;
    }



}
