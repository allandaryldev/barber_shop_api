<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return new \App\Http\Resources\UsersResource($request->user());
});
Route::group([
    'middleware' => ['api'],
    'prefix' => 'v1'
], function () {

    Route::post('/register', [App\Http\Controllers\UsersController::class, 'store']);

    Route::group([
        'middleware' => 'auth:sanctum',
    ], function () {

        Route::apiResources([
            "users" => UsersController::class,
            "customers" => CustomersController::class,
            "services" => ServicesController::class,
            "transactions" => TransactionsController::class,
        ]);

    });
});
