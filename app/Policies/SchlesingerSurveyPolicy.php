<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class SchlesingerSurveyPolicy
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
