<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GameController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\BallController;
use App\Http\Controllers\FrameController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;

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


Route::group(['prefix' => 'v1'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    Route::group(['prefix' => 'users'], function () {
        Route::patch('/first_access', [UserController::class, 'first_access']);
    });

    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', [DashboardController::class, 'index']);
    });

    Route::group(['prefix' => 'groups'], function () {
        Route::patch('/invite/{group}', [GroupController::class, 'reply']);
        Route::post('/invite', [GroupController::class, 'invite']);
        Route::post('/', [GroupController::class, 'store']);
        Route::get('/', [GroupController::class, 'index']);
        Route::delete('/{group}/user/{user}', [GroupController::class, 'removeUser']);
        Route::get('/{group}', [GroupController::class, 'show']);
        Route::get('/{group}/games', [GroupController::class, 'games']);
    });

    Route::group(['prefix' => 'games'], function () {
        Route::get('/ongoing', [GameController::class, 'ongoing']);
        Route::get('/', [GameController::class, 'index']);
        Route::post('/', [GameController::class, 'store']);
        Route::get('/{game}', [GameController::class, 'show']);
        Route::put('/{game}', [GameController::class, 'update']);
    });

    Route::group(['prefix' => 'frames'], function () {
        Route::post('/', [FrameController::class, 'store']);
    });

    Route::group(['prefix' => 'locations'], function () {
        Route::get('/', [LocationController::class, 'index']);
    });

    Route::group(['prefix' => 'balls'], function () {
        Route::get('/', [BallController::class, 'index']);
        Route::post('/', [BallController::class, 'store']);
        Route::put('/{ball}', [BallController::class, 'update']);
        Route::delete('/{ball}', [BallController::class, 'destroy']);
    });
});
