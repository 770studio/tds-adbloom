<?php

use App\Http\Controllers\Api\WidgetController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('/v1/widget/{widget_short_id}/opportunities', [WidgetController::class, 'opportunities']);
/* Route::middleware('auth:api')->group(function () {
 }) ;*/

//Route::get('/v1/opportunities_locator', [OpportunitiesController::class, 'search']);

