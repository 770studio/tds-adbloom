<?php

namespace App\Models\Infrastructure;



class Platform extends ArrayField
{
    const desktop = 1;
    const mobile = 2;
    const tablet = 3;

    /*
     *  [
             1 => "Desktop",
             2 => "Mobile",
             3 => "Tablet",
           ]

     */


}
