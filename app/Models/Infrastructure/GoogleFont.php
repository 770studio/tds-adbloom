<?php

namespace App\Models\Infrastructure;


class GoogleFont extends ArrayDataStruct
{
    public const DEFAULT_FONT = 'Work Sans';
    public static array $BASE_ARRAY = [
        'Andada Pro' => "'Andada Pro', serif;",
        'Anton' => "'Anton', sans-serif;",
        'Archivo' => "'Archivo', sans-serif;",
        'BioRhyme' => "'BioRhyme', serif;",
        'Cormorant' => "'Cormorant', serif;",
        'Encode Sans' => "'Encode Sans', sans-serif;",
        'Epilogue' => "'Epilogue', sans-serif;",
        'Hahmlet' => "'Hahmlet', serif;",
        'Inter' => "'Inter', sans-serif;",
        'JetBrains Mono' => "'JetBrains Mono', monospace;",
        'Lato' => "'Lato', sans-serif;",
        'Lora' => "'Lora', serif;",
        'Manrope' => "'Manrope', sans-serif;",
        'Montserrat' => "'Montserrat', sans-serif;",
        'Nunito' => "'Nunito', sans-serif;",
        'Old Standard TT' => "'Old Standard TT', serif;",
        'Open Sans' => "'Open Sans', sans-serif;",
        'Oswald' => "'Oswald', sans-serif;",
        'Oxygen' => "'Oxygen', sans-serif;",
        'Playfair Display' => "'Playfair Display', serif;",
        'Poppins' => "'Poppins', sans-serif;",
        'Raleway' => "'Raleway', sans-serif;",
        'Roboto' => "'Roboto', sans-serif;",
        'Sora' => "'Sora', sans-serif;",
        'Source Sans Pro' => "'Source Sans Pro', sans-serif;",
        'Spectral' => "'Spectral', serif;",
        'Work Sans' => "'Work Sans', sans-serif;",
    ];

    /**
     * @param mixed $fontID
     * @return string
     */
    public static function getFont($fontID): string
    {
        return self::getLongName($fontID ?? self::DEFAULT_FONT);
    }
}
