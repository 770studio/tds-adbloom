<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GrlRedirectController extends Controller
{
    public function __invoke(Request $request)
    {
        // TODO валидация не работает, мы просто редиректимся на login
        // нужно как-то вынести контроллер из-под новы
        $request->validate([
            'tsid' => 'required|numeric',
        ]);

        dd($request->tsid);
    }
}
