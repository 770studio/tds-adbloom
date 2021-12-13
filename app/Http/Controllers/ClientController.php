<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Infrastructure\Click;
use Illuminate\Http\RedirectResponse;

class ClientController extends Controller
{

    public function trackOpportunity(Click $click, Client $client, string $redirect_status_str): RedirectResponse
    {
        return $click->handle($redirect_status_str);
    }
}

