<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

// user controller routes

Route::post("login", [UserController::class, "login"]);
Route::post("register", [UserController::class, "register"]);

// sanctum auth middleware routes

Route::middleware('auth:api')->prefix('user/')->group(function() {
    Route::post("authUserInfo", [UserController::class, "user"]);
    Route::post("usersList", [UserController::class, "users"]);
    Route::post("userModify", [UserController::class, "modify"]);

});
