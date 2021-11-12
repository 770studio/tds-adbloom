<?php

namespace App\Interfaces;

use App\Models\Partner;

interface GeneralResearchAPIServiceIF
{
    public function makeRequest(): object;

    public function setPartner(Partner $partner): self;
}
