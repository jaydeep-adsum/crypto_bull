<?php

use App\Http\Controllers\API\UserController;
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

Route::namespace('Api')->group(
    function () {
//        Route::get('swagger', 'SwaggerController@listItem');
        Route::post('signup', [UserController::class, 'signup']);
        Route::post('login', [UserController::class, 'login']);

        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('user',[UserController::class,'index']);
            Route::post('edit-tether-account',[UserController::class,'update']);
        });
    });
