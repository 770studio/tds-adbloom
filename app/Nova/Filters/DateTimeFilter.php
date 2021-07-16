<?php

namespace App\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Nova\Filters\DateFilter;

class DateTimeFilter extends DateFilter
{
    /**
     * Apply the filter to the given query.
     *
     * @param Request $request
     * @param Builder $query
     * @param mixed $value
     * @return Builder
     */
    public function apply(Request $request, $query, $value)
    {


        return $query->whereDate('Stat_datetime', Carbon::parse($value)->toDateString());

    }
}
