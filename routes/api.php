<?php

use App\Http\Controllers\Api\WidgetController;
use App\Http\Controllers\GRLController;
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

//Route::get('/v1/widget/{widget_short_id}/opportunities', [WidgetController::class, 'opportunities']);
//Route::get('/v1/widget/{widget_short_id}/opportunities', [WidgetController::class, 'opportunities']);
// /api/v1/widget/SVLSaRWEFhiTd6TVT4FjT/opportunities/grl

Route::prefix('v1/widget/{widget_short_id}/opportunities')->group(function () {

    Route::get('/', [WidgetController::class, 'opportunities']);
    Route::get('/grl', [GRLController::class, 'proxy']);
});



// /redirect/grl?tsid={VALUE}

/* Route::middleware('auth:api')->group(function () {
 }) ;*/

//Route::get('/v1/opportunities_locator', [OpportunitiesController::class, 'search']);

