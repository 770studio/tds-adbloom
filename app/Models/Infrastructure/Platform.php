<?php

namespace App\Models\Infrastructure;



class Platform  extends ArrayField
{
    const Desktop = 1;
    const Mobile = 2;
    const Tablet = 3;

    /*
     *  [
             1 => "Desktop",
             2 => "Mobile",
             3 => "Tablet",
           ]

     */


}
