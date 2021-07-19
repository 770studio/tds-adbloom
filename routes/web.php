<?php

use App\Http\Controllers\ClientController;
use App\Models\Client;
use App\Models\RedirectStatus;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//{clientID}/{surveyID}/success/?clickid=ABC1
Route::get('/redirect/{client:short_id}/{redirect_status}', [ClientController::class, 'trackOpportunity']);

/*Route::get('/redirect/{client:short_id}/{redirect_status:code}', function (Client $client, $surveyID, RedirectStatus $redirect_status){
    dd($client, $surveyID, $redirect_status);
});*/


Route::get('/', function () {
    return view('welcome');
});
