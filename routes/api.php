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
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\RankingController;

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
        Route::post('/forgot_password', [AuthController::class, 'forgotPassword']);
        Route::post('/validate_password_code', [AuthController::class, 'validatePassworCode']);
        Route::put('/reset_password', [AuthController::class, 'resetPassword']);

    });

    Route::group(['prefix' => 'users'], function () {
        Route::delete('/', [UserController::class, 'destroy']);
        Route::put('/', [UserController::class, 'update']);
        Route::patch('/push_token', [UserController::class, 'pushToken']);
        Route::patch('/first_access', [UserController::class, 'firstAaccess']);
        Route::post('/avatar', [UserController::class, 'avatar']);
        Route::put('/password', [UserController::class, 'password']);
    });

    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('/monthly/{year}/{month}', [DashboardController::class, 'monthly']);
        Route::get('/weekly/{week}', [DashboardController::class, 'weekly']);
        Route::get('/yearly/{year}', [DashboardController::class, 'yearly']);
        Route::get('/version', [DashboardController::class, 'version']);
    });

    Route::group(['prefix' => 'rankings'], function () {
        Route::get('/', [RankingController::class, 'index']);
    });

    Route::group(['prefix' => 'pdfs'], function () {
        Route::get('/game/{game}', [PdfController::class, 'generateGame']);
    });

    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::patch('/{notification}', [NotificationController::class, 'toggleRead']);
        Route::post('/', [NotificationController::class, 'store']);
    });

    Route::group(['prefix' => 'groups'], function () {
        Route::patch('/invite/{group}', [GroupController::class, 'reply']);
        Route::post('/invite', [GroupController::class, 'invite']);
        Route::post('/', [GroupController::class, 'store']);
        Route::get('/', [GroupController::class, 'index']);
        Route::put('/{group}', [GroupController::class, 'update']);
        Route::delete('/{group}/user/{user}', [GroupController::class, 'removeUser']);
        Route::delete('/{group}', [GroupController::class, 'destroy']);
        Route::get('/{group}', [GroupController::class, 'show']);
        Route::get('/{group}/games', [GroupController::class, 'games']);
    });

    Route::group(['prefix' => 'games'], function () {
        Route::get('/ongoing', [GameController::class, 'ongoing']);
        Route::get('/', [GameController::class, 'index']);
        Route::post('/', [GameController::class, 'store']);
        Route::get('/{game}', [GameController::class, 'show']);
        Route::put('/{game}', [GameController::class, 'update']);
        Route::delete('/{game}', [GameController::class, 'destroy']);
    });

    Route::group(['prefix' => 'frames'], function () {
        Route::post('/', [FrameController::class, 'store']);
        Route::put('/{frame}', [FrameController::class, 'update']);
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
