<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Plans\PlanController;
use App\Http\Controllers\Api\Plans\PlanUserController;
use App\Http\Controllers\Api\Users\UserConfigController;
use App\Http\Controllers\Api\Servers\ServerController;
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

/*
 * Route without auth
 *
 * */
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'loginUser'])->name('auth.login-user');
});

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
   Route::apiResource('plans', PlanUserController::class);
   Route::apiResource('config', UserConfigController::class);
   Route::get('/info', [AuthController::class, 'userInfo'])->name('auth.info-user');

});

Route::apiResource('plans', PlanController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/servers', [ServerController::class, 'index']);
});

