<?php


namespace App\Helpers;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class DBFieldsHelper
{
    /**
     * @throws Exception
     */
    public function __construct(string $table)
    {
        if (
            !DB::connection()->getDoctrineSchemaManager()
                ->tablesExist($table)
        ) {
            throw new Exception('DBFieldsHelper: table doesnt exist');
        }

        $this->tableName = $table;

    }

    public function cast(string $dbFieldName, ?string $value): ?string
    {
        if (!$value) {
            return $value;
        }

        switch ($this->getDBFieldType($dbFieldName)) {
            case 'datetime':
                return Carbon::parse($value)->toDateTimeString();
            case 'date':
                return Carbon::parse($value)->toDateString();
            default:
                return $value;
        }
    }

    private function getDBFieldType($fieldName): string
    {
        return DB::connection()->getDoctrineColumn($this->tableName, $fieldName)
            ->getType()
            ->getName();

    }

}
