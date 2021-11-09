<?php

namespace App\Console\Commands\StatsAlerts;

use App\Helpers\DataTransferObject;
use App\Services\StatsAlerts\Period24h;
use InvalidArgumentException;

class AlertDTO extends DataTransferObject
{
    public string $direction;
    public string $offer_name;
    public float $diff_prs;
    public Period24h $recent_period, $older_period;
    public float $recent_item_prs_value, $older_item_prs_value;

    protected function __construct(array $parameters = [])
    {
        parent::__construct($parameters);
        self::validate($parameters);

    }

    public static function fromArray(array $params): self
    {

        return new self([
            'direction' => $params['direction'],
            'diff_prs' => $params['diff_prs'],
            'recent_period' => $params['recent_period'],
            'older_period' => $params['older_period'],
            'recent_item_prs_value' => $params['recent_item_prs_value'],
            'older_item_prs_value' => $params['older_item_prs_value'],
            'offer_name' => $params['offer_name']

        ]);

    }

    private static function validate(array $params): void
    {
        if (!in_array($params['direction'], ['UP', 'DOWN'])) {
            throw new InvalidArgumentException('direction can be UP or DOWN');
        }
    }
}
