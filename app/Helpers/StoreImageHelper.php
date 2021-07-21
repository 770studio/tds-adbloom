<?php


namespace App\Helpers;



use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class StoreImageHelper
{
    public static function getCreativeAssetUniqueName(int $modelId, UploadedFile $file) : string
    {
        $postfix = 0;
        $unique_name = self::getUniqueName($modelId, $file) ;
        while(Storage::disk('creatives')->exists(
            $modelId . DIRECTORY_SEPARATOR . $unique_name

            )
        )
        {
            $unique_name = self::getUniqueName($modelId, $file, ++$postfix);
        }

        return $unique_name;
    }

    private static function getUniqueName(int $modelId, UploadedFile $file,  $postfix = '') : string
    {
        return sha1($modelId . $file->getClientOriginalName() . $postfix) . '.' . $file->getClientOriginalExtension();
     }
}
