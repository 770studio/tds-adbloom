<?php

namespace App\Services\StatsAlerts;
//TODO REFACTOR THAT SHIT
class Group
{
    public $all =
        [
            "Stat_affiliate_id",
            "Stat_offer_id",
            "Stat_offer_url_id",
            "Stat_goal_id",
            "Stat_date",
            "Stat_hour"
        ];


    public function get(): array
    {
        return $this->all;
    }


    public function by(array $params): void
    {
        foreach ($params as $criteria => $param) {
            if (!$param) {
                switch ($criteria) {
                    case 'partners' :
                        unset($this->all[array_search("Stat_affiliate_id", $this->all)]);
                        break;
                    case 'hour' :
                        unset($this->all[array_search("Stat_hour", $this->all)]);
                        break;

                }
            }
        }
    }


}
