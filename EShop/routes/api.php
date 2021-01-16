<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ImageController;

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
Route::get("category/getParents", [GlobalController::class, "getParents"]);



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
    Route::post("", [CategoryController::class, "store"]);
    Route::put("{id}", [CategoryController::class, "update"]);
    Route::post("search", [CategoryController::class, "search"]);
    Route::post("addProperties", [CategoryController::class, "addProperties"]);
    Route::get("{id}/getProperties", [CategoryController::class, "getProperties"]);
});

//product route
Route::post("products/search", [ProductController::class, "search"]);
Route::post('product/image/upload', [ImageController::class, "upload"])->name('image.upload');
Route::middleware('auth:api')->prefix('product/')->group(function() {
    Route::post("", [ProductController::class, "store"]);
    Route::put("{id}", [ProductController::class, "update"]);
    Route::post("search", [ProductController::class, "search"]);
//    Route::post('image/upload', [ImageController::class, "upload"])->name('image.upload');
    Route::delete('image/{id}', [ImageController::class, "destroy"])->name('image.delete');
//    Route::post("addProperties", [ProductController::class, "addProperties"]);
});
