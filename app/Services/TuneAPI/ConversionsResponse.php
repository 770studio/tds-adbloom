<?php


namespace App\Services\TuneAPI;


use App\Models\Conversion;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ConversionsResponse extends Response
{
    private static $dbFieldTypes = [];

    public function parseData(): Collection
    {
        $data = [];
        collect($this->apiResult->response->data->data)
            ->transform(function ($items, $numkey) use (&$data) {
                //return $item->{$entity};
                foreach ($items as $UpperLevelKey => $item_Arr) {
                    foreach ($item_Arr as $itemkey => $val) {
                        #TODO str macro toMysqlFieldname and ViseVersa
                        if (in_array($UpperLevelKey . '.' . $itemkey, Conversion::FIELDS)) {
                            $dbFieldName = $UpperLevelKey . '_' . $itemkey;
                            $data[$numkey][$dbFieldName] = $this->cast($dbFieldName, $val);
                        }


                    }
                }

            });
        $this->data = collect($data);
        return $this->data;
    }
    #TODO move somewhere
    private function cast(string $dbFieldName, $val): ?string
    {
        if(!$val) return $val;

        switch($this->getDBFieldType($dbFieldName)) {
            case 'datetime': return Carbon::parse($val)->toDateTimeString();
            case 'date': return Carbon::parse($val)->toDateString();
            default: return $val;
        }
    }

    #TODO move somewhere
    private function getDBFieldType($dbFieldName)
    {
        if(!isset(self::$dbFieldTypes[$dbFieldName]))
        {
            self::$dbFieldTypes[$dbFieldName] = DB::connection()->getDoctrineColumn('conversions', $dbFieldName)->getType()->getName();
        }

        return self::$dbFieldTypes[$dbFieldName];

    }
}
