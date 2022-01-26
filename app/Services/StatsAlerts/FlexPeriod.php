<?php

namespace App\Services\StatsAlerts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;

class FlexPeriod implements Arrayable
{

    private const DATETIME_FIELD = 'StatDateTime';
    private string $timezone;
    private Carbon $dateStart;
    private Carbon $dateEnd;
    /** @var mixed */
    private $periodCode;

    public function __construct($period)
    {
        $this->timezone = config('services.tune_api.stats_timezone');
        $this->periodCode = $period;

        switch ($period) {
            /*
             * e.g. today is oct 16 , we are aiming for period
             * from sept 15 startOfDay to oct 14 endOfDay (30 days total)
             * to be able to compare this period to last day which is yesterday oct 16
             */
            case 'last30d':
                $this->setDates(
                    $this->getNewMutableNowInst()->subDays(32)->startOfDay(),
                    $this->getNewMutableNowInst()->subDays(2)->endOfDay()
                );
                break;
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
            /*            case '24n': //   n=0 is for last24h, 1 - prev24h, and so on
                            $n = (int)$period;
                            $this->setDates(
                                $this->getNewMutableNowInst()->subHours(24 * ($n + 1)),
                                $this->getNewMutableNowInst()->subHours(24 * $n)
                            );
                            break;*/

            default: // n days ago
                $this->setDates(
                    $this->getNewMutableNowInst()->subDays((int)$period)->startOfDay(),
                    $this->getNewMutableNowInst()->subDays((int)$period)->endOfDay()
                );

            // throw new \LogicException('period dates are not set. undefined period');
        }
    }

    public function setCustomDates(Carbon $start, Carbon $end): void
    {
        $this->dateStart = $start;
        $this->dateEnd = $end;
    }

    private function setDates(Carbon $start, Carbon $end): void
    {
        $this->dateStart = $start;
        $this->dateEnd = $end;
    }

    private function getNewMutableNowInst(): Carbon
    {
        return Carbon::now()->timezone($this->timezone);
    }

    public function getDateRange(): array
    {
        return [$this->dateStart, $this->dateEnd];
    }

    public function getStartDate(): string
    {
        return $this->dateStart->toDateTimeString();
    }

    public function getEndDate(): string
    {
        return $this->dateEnd->toDateTimeString();
    }

    public function toArray(): array
    {
        return [
            [self::DATETIME_FIELD, '>=', $this->dateStart->toDateTimeString()],
            [self::DATETIME_FIELD, '<', $this->dateEnd->toDateTimeString()],
        ];
    }

    public function getName()
    {
        return $this->periodCode;
    }
}
