<?php


namespace App\Services\TuneAPI;


use Illuminate\Support\Collection;

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
                        $data[$numkey][$UpperLevelKey . '_' . $itemkey] = $val;

                        //  DB::connection()->getDoctrineColumn('users', 'age')->getType()->getName()
                    }
                }

            });
        $this->data = collect($data);
        return $this->data;
    }


}
