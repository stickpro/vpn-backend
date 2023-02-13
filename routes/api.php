<?php

use App\Http\Controllers\Api\AuthController;
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

/*
 * Route without auth
 *
 * */
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'createUser'])->name('auth.create-user');
    Route::post('/login', [AuthController::class, 'loginUser'])->name('auth.login-user');
});


Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    Route::get('/info', [AuthController::class, 'userInfo'])->name('auth.info-user');
});

