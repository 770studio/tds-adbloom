<?php


namespace App\Helpers;



use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StoreImageHelper
{
    public static function getCreativeAssetUniqueName(UploadedFile $file): string
    {
        $postfix = 0;
        $unique_name = self::getUniqueName($file);
        while (Storage::disk('creatives')->exists($unique_name)
        ) {
            $unique_name = self::getUniqueName($file, ++$postfix);
        }

        return $unique_name;
    }

    private static function getUniqueName(UploadedFile $file, $postfix = ''): string
    {
        return sha1($file->getClientOriginalName() . $postfix) . '.' . $file->getClientOriginalExtension();
    }
}
