<?php

namespace App;

use Everestmx\BelongsToManyField\BelongsToManyField;
use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Fields\ResourceRelationshipGuesser;

class MyBelongsToManyField extends BelongsToManyField
{
    public function __construct($name, $attribute = null, $resource = null)
    {
        parent::__construct($name, $attribute, $resource);

        $resource = $resource ?? ResourceRelationshipGuesser::guessResource($name);

        $this->resource = $resource;
        $this->resourceClass = $resource;
        $this->resourceName = $resource::uriKey();
        $this->manyToManyRelationship = $this->attribute;

        $this->fillUsing(function ($request, $model, $attribute, $requestAttribute) use ($resource) {
            if (is_subclass_of($model, Model::class)) {
                $model::saved(function ($model) use ($attribute, $request) {
                    $attr = json_decode($request->$attribute, true);
                    if (!$attr) return;
                    $values = array_column($attr, 'id');
                    $model->$attribute()->sync($values);
                });
                unset($request->$attribute);
            }
        });
    }

}
