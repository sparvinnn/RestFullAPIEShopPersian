<?php

use App\Http\Controllers\FilterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CartController;
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
Route::get('home', [HomeController::class, "index"])->name('home');
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, "login"])->name('login');
    Route::post('logout', [AuthController::class, "logout"]);
    Route::post('refresh', [AuthController::class, "refresh"]);
    Route::post('me', [AuthController::class, "me"]);
});
//Route::post("login",
//    LoginController::class);

//Route::post("login", [UserController::class, "login"]);
//Route::get("user", [UserController::class, "me"])->middleware('auth:sanctum');
Route::post("register", [UserController::class, "register"]);

Route::get("counties", [GlobalController::class, "counties"]);
Route::get("cities", [GlobalController::class, "cities"]);
Route::get("roles", [GlobalController::class, "roles"]);
Route::get("category/getParents", [GlobalController::class, "getParents"]);
Route::get("category/{id}/getChildren", [GlobalController::class, "getChildren"]);
Route::post("category/search", [CategoryController::class, "search"]);
Route::get("category/{id}/getProperties", [CategoryController::class, "getProperties"]);
Route::get("category/{id}/updateProperties", [CategoryController::class, "updateProperties"]);
Route::get("categories", [GlobalController::class, "getAllCategory"]);
Route::post("product/search", [GlobalController::class, "getProducts"]);
Route::post("getBrands", [GlobalController::class, "getBrands"]);
Route::get("getAllBrands", [GlobalController::class, "getAllBrands"]);


//filter routes
Route::get("properties", [FilterController::class, "getProperties"]);
Route::post("filter", [FilterController::class, "filter"]);

// sanctum auth middleware routes

Route::middleware('jwt.verify')->prefix('user/')->group(function() {
//    Route::get("me", [UserController::class, "me"]);
    Route::post("usersList", [UserController::class, "users"]);
    Route::post("userModify", [UserController::class, "modify"]);
    Route::post("userSearch", [UserController::class, "search"]);
//    Route::post("userCreate", [UserController::class, "search"]);
});

//branch route
Route::middleware('jwt.verify')->get('branch/search', [BranchController::class, "search"]);
Route::middleware('jwt.verify')->resource('branch', BranchController::class);

//brand route
Route::middleware('jwt.verify')->prefix('brand/')->group(function() {
    Route::post("", [BrandController::class, "store"]);
    Route::put("{id}", [BrandController::class, "update"]);
});

//cart route
Route::middleware('jwt.verify')->prefix('cart/')->group(function() {
    Route::get("", [CartController::class, "getCart"]);
    Route::post("", [CartController::class, "addToCard"]);
    Route::post("group", [CartController::class, "addAllToCard"]);
//    Route::put("{id}", [CartController::class, "update"]);
    Route::delete("{id}", [CartController::class, "delete"]);
//
//    Route::post("addProperties", [CartController::class, "addProperties"]);
//    Route::post("updateProperties", [CartController::class, "updateProperties"]);

});

//cart route
Route::middleware('jwt.verify')->prefix('favorite/')->group(function() {
    Route::get("", [FavoriteController::class, "getFavorite"]);
    Route::post("", [FavoriteController::class, "addToFavorite"]);
//    Route::put("{id}", [CartController::class, "update"]);
    Route::delete("{id}", [FavoriteController::class, "delete"]);
//
//    Route::post("addProperties", [CartController::class, "addProperties"]);
//    Route::post("updateProperties", [CartController::class, "updateProperties"]);

});

//category route
Route::middleware('jwt.verify')->prefix('category/')->group(function() {
    Route::post("", [CategoryController::class, "store"]);
    Route::put("{id}", [CategoryController::class, "update"]);
    Route::delete("{id}", [CategoryController::class, "delete"]);

    Route::post("addProperties", [CategoryController::class, "addProperties"]);
    Route::post("updateProperties", [CategoryController::class, "updateProperties"]);

});

//product route
Route::post('product/image/upload', [ImageController::class, "upload"])->name('image.upload');
Route::middleware('jwt.verify')->prefix('product/')->group(function() {
    Route::post("", [ProductController::class, "store"]);
    Route::put("{id}", [ProductController::class, "update"]);
    Route::delete("{id}", [ProductController::class, "delete"]);
    Route::post("adminSearch", [ProductController::class, "search"]);
//    Route::post('image/upload', [ImageController::class, "upload"])->name('image.upload');
    Route::delete('image/{id}', [ImageController::class, "destroy"])->name('image.delete');
//    Route::post("addProperties", [ProductController::class, "addProperties"]);
});
