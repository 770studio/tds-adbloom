<?php


namespace App\Services\TuneAPI;


use App\Helpers\DBFieldsHelper;
use App\Services\Response;
use Exception;
use Illuminate\Support\Collection;

class ConversionsResponse extends Response
{


    /**
     * @throws Exception
     */
    public function parseData(): Collection
    {
        $dbFieldsHelper = (new DBFieldsHelper($this->relModel->getTable()));

        collect($this->apiResult->response->data->data)
            ->transform(function ($items, $numkey) use (&$data, $dbFieldsHelper) {

                foreach ($items as $UpperLevelKey => $item_Arr) {
                    foreach ($item_Arr as $itemkey => $val) {
                        #TODO str macro toMysqlFieldname and ViseVersa
                        if (in_array($UpperLevelKey . '.' . $itemkey, $this->relModel::TUNE_FIELDS, false)) {
                            $dbFieldName = $UpperLevelKey . '_' . $itemkey;
                            $data[$numkey][$dbFieldName] = $dbFieldsHelper->cast($dbFieldName, $val);
                        }


                    }

                }

            });

        $this->data = collect($data);


        return $this->data;
    }

    /**
     * @throws Exception
     */
    public function validate(): self
    {
        if ($this->apiResult->response->errorMessage) {
            throw new Exception($this->apiResult->response->errorMessage);
        }
        return $this;
    }


}
