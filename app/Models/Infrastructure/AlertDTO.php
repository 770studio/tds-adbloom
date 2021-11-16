<?php

namespace App\Models\Infrastructure;

use App\Helpers\DataTransferObject;
use App\Services\StatsAlerts\FlexPeriod;
use InvalidArgumentException;

class AlertDTO extends DataTransferObject
{
    public string $direction;
    public string $offer_name;
    public float $diff_prs;
    public FlexPeriod $recent_period, $older_period;
    public float $recent_item_prs_value, $older_item_prs_value;
    public int $recent_conversions, $recent_clicks = 0;

    protected function __construct(array $parameters = [])
    {
        parent::__construct($parameters);
        self::validate($parameters);

    }

    public static function fromArray(array $params): self
    {
        return new self($params);
    }

    private static function validate(array $params): void
    {
        if (!in_array($params['direction'], ['UP', 'DOWN'])) {
            throw new InvalidArgumentException('direction can be UP or DOWN');
        }
    }
}
