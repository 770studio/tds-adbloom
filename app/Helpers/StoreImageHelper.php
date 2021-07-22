<?php


namespace App\Helpers;



use App\Models\Opportunity;
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

    public static function getOpportunityAssetUrl(Opportunity $opportunity): string
    {
        return Storage::disk('creatives')->url($opportunity->image);
    }

    public static function getOpportunityAssetCDNUrl(Opportunity $opportunity): string
    {
        return Storage::disk('creatives_cdn')->url($opportunity->image);
    }

    private static function getUniqueName(UploadedFile $file, $postfix = ''): string
    {
        return sha1($file->getClientOriginalName() . $postfix) . '.' . $file->getClientOriginalExtension();
    }
}
