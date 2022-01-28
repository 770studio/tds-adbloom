<?php

namespace App\Helpers;


class GoogleFontsHelper
{
    public const FONTS = [
        'Andada Pro' => "font-family: 'Andada Pro', serif;",
        'Anton' => "font-family: 'Anton', sans-serif;",
        'Archivo' => "font-family: 'Archivo', sans-serif;",
        'BioRhyme' => "font-family: 'BioRhyme', serif;",
        'Cormorant' => "font-family: 'Cormorant', serif;",
        'Encode Sans' => "font-family: 'Encode Sans', sans-serif;",
        'Epilogue' => "font-family: 'Epilogue', sans-serif;",
        'Hahmlet' => "font-family: 'Hahmlet', serif;",
        'Inter' => "font-family: 'Inter', sans-serif;",
        'JetBrains Mono' => "font-family: 'JetBrains Mono', monospace;",
        'Lato' => "font-family: 'Lato', sans-serif;",
        'Lora' => "font-family: 'Lora', serif;",
        'Manrope' => "font-family: 'Manrope', sans-serif;",
        'Montserrat' => "font-family: 'Montserrat', sans-serif;",
        'Nunito' => "font-family: 'Nunito', sans-serif;",
        'Old Standard TT' => "font-family: 'Old Standard TT', serif;",
        'Open Sans' => "font-family: 'Open Sans', sans-serif;",
        'Oswald' => "font-family: 'Oswald', sans-serif;",
        'Oxygen' => "font-family: 'Oxygen', sans-serif;",
        'Playfair Display' => "font-family: 'Playfair Display', serif;",
        'Poppins' => "font-family: 'Poppins', sans-serif;",
        'Raleway' => "font-family: 'Raleway', sans-serif;",
        'Roboto' => "font-family: 'Roboto', sans-serif;",
        'Sora' => "font-family: 'Sora', sans-serif;",
        'Source Sans Pro' => "font-family: 'Source Sans Pro', sans-serif;",
        'Spectral' => "font-family: 'Spectral', serif;",
        'Work Sans' => "font-family: 'Work Sans', sans-serif;",
    ];

    /**
     * @param string $longName
     * @return false|string
     */
    public static function getShortName(string $longName)
    {
        return array_search($longName, self::FONTS, true);
    }

    public static function getLongName(string $shortName): ?string
    {
        return self::FONTS[$shortName] ?? null;
    }

    public static function getLabels(): array
    {
        $keys = array_keys(self::FONTS);
        return array_combine($keys, $keys);
    }

}
