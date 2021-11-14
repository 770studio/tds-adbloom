<?php


namespace App\Helpers;



use App\Models\Opportunity;
use App\Models\Partner;
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

    public static function getOpportunityAssetCDNUrl(Opportunity $opportunity): ?string
    {
        return self::getCreativesCDNUrl($opportunity->image);

    }
    public static function getPartnerPointsLogoAssetCDNUrl(Partner $partner): ?string
    {
        return self::getCreativesCDNUrl($partner->points_logo);
    }

    public static function getCreativesCDNUrl(?string $local_path): ?string
    {
        if (filter_var($local_path, FILTER_VALIDATE_URL)) {
            return $local_path; // its an url already
        }
        return Storage::disk('creatives_cdn')->exists($local_path)
            ? Storage::disk('creatives_cdn')->url($local_path)
            : null;

    }
    private static function getUniqueName(UploadedFile $file, $postfix = ''): string
    {
        return sha1($file->getClientOriginalName() . $postfix) . '.' . $file->getClientOriginalExtension();
    }
}
