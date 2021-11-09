<?php

namespace App\Services\StatsAlerts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;

class Period24h implements Arrayable
{

    private const DATETIME_FIELD = 'StatDateTime';
    private string $timezone;
    private Carbon $dateSrart;
    private Carbon $dateEnd;

    public function __construct($period)
    {
        $this->timezone = config('services.tune_api.stats_timezone');

        switch ($period) {
            case 'prev24h':
                $this->setDates(
                    $this->getNewMutableNowInst()->subHours(48),
                    $this->getNewMutableNowInst()->subHours(24)
                );
                break;
            case 'last24h':
                $this->setDates(
                    $this->getNewMutableNowInst()->subHours(24),
                    $this->getNewMutableNowInst()
                );
                break;
            case 'lastDay':
                $this->setDates(
                    $this->getNewMutableNowInst()->subDay()->startOfDay(),
                    $this->getNewMutableNowInst()->subDay()->endOfDay()
                );
                break;
            case 'dayBeforelastDay':
                $this->setDates(
                    $this->getNewMutableNowInst()->subDays(2)->startOfDay(),
                    $this->getNewMutableNowInst()->subDays(2)->endOfDay()
                );
                break;
            case '24n': //   n=0 is for last24h, 1 - prev24h, and so on
                $n = (int)$period;
                $this->setDates(
                    $this->getNewMutableNowInst()->subHours(24 * ($n + 1)),
                    $this->getNewMutableNowInst()->subHours(24 * $n)
                );
                break;

            default: // n days ago
                $this->setDates(
                    $this->getNewMutableNowInst()->subDays((int)$period)->startOfDay(),
                    $this->getNewMutableNowInst()->subDays((int)$period)->endOfDay()
                );

            // throw new \LogicException('period dates are not set. undefined period');
        }
    }

    private function setDates(Carbon $start, Carbon $end): void
    {
        $this->dateSrart = $start;
        $this->dateEnd = $end;
    }

    private function getNewMutableNowInst(): Carbon
    {
        return Carbon::now()->timezone($this->timezone);
    }

    public function getDateRange(): array
    {
        return [$this->dateSrart, $this->dateEnd];
    }

    public function getStartDate(): string
    {
        return $this->dateSrart->toDateTimeString();
    }

    public function getEndDate(): string
    {
        return $this->dateEnd->toDateTimeString();
    }

    public function toArray(): array
    {
        return [
            [self::DATETIME_FIELD, '>=', $this->dateSrart->toDateTimeString()],
            [self::DATETIME_FIELD, '<', $this->dateEnd->toDateTimeString()],
        ];
    }
}
