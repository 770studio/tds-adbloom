<?php


namespace App\Services\TuneAPI;


use App\Models\Conversion;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ConversionsResponse extends Response
{

    public function parseData(): Collection
    {
        $data = [];
        collect($this->apiResult->response->data->data)
            ->transform(function ($items, $numkey) use (&$data) {
                //return $item->{$entity};
                foreach ($items as $UpperLevelKey => $item_Arr) {
                    foreach ($item_Arr as $itemkey => $val) {
                        #TODO str macro toMysqlFieldname and ViseVersa

                        #TODO refactor - takes much time , redundant operations
                        if(in_array($UpperLevelKey . '.' . $itemkey, Conversion::FIELDS))
                        $dbFieldName = $UpperLevelKey . '_' . $itemkey;
                        $dbFieldType = DB::connection()->getDoctrineColumn('conversions', $dbFieldName)->getType()->getName();
                        $data[$numkey][$dbFieldName] = $this->cast($dbFieldType, $val);

                    }
                }

            });
        $this->data = collect($data);
        return $this->data;
    }

    private function cast(string $type, $val): ?string
    {
        if(!$val) return $val;

        switch($type) {
            case 'datetime': return Carbon::parse($val)->toDateTimeString();
            case 'date': return Carbon::parse($val)->toDateString();
            default: return $val;
        }
    }


}
