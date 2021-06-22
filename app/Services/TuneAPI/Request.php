<?php


namespace App\Services\TuneAPI;


use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Tune\Utils\Operator;

class Request implements Arrayable
{

    private $request = [];

    public function __construct()
    {
    }

    function toArray(): array
    {
        return $this->request;
    }

    function filter($field, $operator, $value): self
    {

        return $this->addParam('filters' , [
            $field => [
                $operator => $value
            ]
        ] );

    }

    function limit($limit)
    {
        return $this->setParam('limit' , $limit );

    }
    function sortBy($sort, $asc=true)
    {

        return $this->setParam('sort' , [$sort => $asc ? 'ASC' : 'DESC'] );

    }
    function page($page)
    {

        return $this->setParam('page' , $page );
    }

    /**
     * @param string $itemKey
     * @param mixed $itemValue
     * @return $this
     */
    private function setParam(string $itemKey, $itemValue)
    {
        $this->request = array_merge(
            $this->request,
            [$itemKey =>  $itemValue]
        );
        return $this;
    }
    private function addParam(string $itemKey, array $itemValue)
    {
        $this->request[$itemKey] = array_merge(
            optional($this->request)[$itemKey] ?? [],
            $itemValue
        );
        return $this;
    }


/*
    function findAllGreaterThanOrEqual($last_x_months, $field): array
    {
        return [
            'filters' => [
                $field => [
                    Operator::GREATER_THAN_OR_EQUAL_TO => now()
                        ->subMonths($last_x_months)
                        ->toDateTimeString(),
                ]
            ],
            //'fields' => [],
            'limit' => self::LIMIT_PER_PAGE
        ];
    }

    function findAllBetween(Carbon $date1, Carbon $date2, string $field): array
    {
        return [
            'filters' => [
                $field => [
                    'conditional' => Operator::BETWEEN,
                    'values' => [
                        $date1->toDateTimeString(),
                        $date2->toDateTimeString()
                    ]
                ]
            ],

            'limit' => self::LIMIT_PER_PAGE
        ];
    }*/


}
