<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    /**
     * Get all of the opportunities that are assigned this tag.
     */

    public function opportunities()
    {
        return $this->morphedByMany(Opportunity::class, 'taggable');
    }
}
