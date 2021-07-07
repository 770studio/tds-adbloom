<?php


namespace App\Models;


use App\Helpers\ShortIdGeneratorHelper;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected static function boot()
    {

        static::creating(function ($entity) {
             $entity->short_id = ShortIdGeneratorHelper::genIdForAnEntity($entity);
        });
        parent::boot();
        //static::addGlobalScope(new TeamScope);


    }

}
