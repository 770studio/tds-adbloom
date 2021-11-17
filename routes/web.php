<?php

use App\Http\Controllers\Api\GRLController;
use App\Http\Controllers\ClientController;
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
Route::get('/redirect/{client:short_id}/{redirect_status}', [ClientController::class, 'trackOpportunity'])->name('track_opportunity');
// /redirect/grl?tsid={VALUE}
// TODO need to upgrade route service provider to 8
Route::get('/redirect/grl', [GRLController::class, 'redirect']);


Route::get('/', function () {
    return view('welcome');
});
