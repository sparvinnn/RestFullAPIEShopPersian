<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\BranchController;

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

Route::get("counties", [GlobalController::class, "counties"]);
Route::get("cities", [GlobalController::class, "cities"]);
Route::get("roles", [GlobalController::class, "roles"]);



// sanctum auth middleware routes

Route::middleware('auth:api')->prefix('user/')->group(function() {
    Route::post("authUserInfo", [UserController::class, "user"]);
    Route::post("usersList", [UserController::class, "users"]);
    Route::post("userModify", [UserController::class, "modify"]);
    Route::post("userSearch", [UserController::class, "search"]);
});

//branch route
Route::middleware('auth:api')->get('branch/search', [BranchController::class, "search"]);
Route::middleware('auth:api')->resource('branch', BranchController::class);

//category route
Route::middleware('auth:api')->prefix('category/')->group(function() {
    Route::post("", [UserController::class, "store"]);
    Route::put("/{id}", [UserController::class, "update"]);
    Route::post("search", [UserController::class, "search"]);
});

