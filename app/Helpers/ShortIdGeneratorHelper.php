<?php


namespace App\Helpers;

use Hidehalo\Nanoid\Client;
use Hidehalo\Nanoid\GeneratorInterface;
use Illuminate\Database\Eloquent\Model;

class ShortIdGeneratorHelper
{

    public static function genIdForAnEntity(Model $entity): string
    {
        $short_id = self::gen(21);
        while($entity->where('short_id', $short_id )->count()) {
            $short_id = self::gen(21);
        }
        return $short_id;
    }

    public static function gen(int $length)
    {
        $client = new Client();
        # default random generator
        //echo $client->generateId($size = 21);
        # more safer random generator
        return $client->generateId($size = $length, $mode = Client::MODE_DYNAMIC);
    }


}
