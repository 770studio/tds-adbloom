<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class DaliaOffersPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function view(): bool
    {
        return true;
    }
}
