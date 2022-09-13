<?php

use App\Http\Controllers\FilterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\PropertiesController;
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

Route::get("with-product-id/{id}", [PropertiesController::class, "listWithProductId"]);
Route::get("get_properties/{id}", [ProductController::class, "get_properties"]);
Route::get("adminSearch", [ProductController::class, "search"]);
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('test', [\App\Http\Controllers\testController::class, "test"])->name('test');
Route::get("withParent", [CategoryController::class, "withParent"]);
// user controller routes
Route::get('home', [HomeController::class, "index"])->name('home');
Route::get('global/banners', [BannerController::class, "filter"])->name('banners');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, "login"])->name('login');
    Route::post('logout', [AuthController::class, "logout"]);
    Route::post('refresh', [AuthController::class, "refresh"]);
    Route::post('me', [AuthController::class, "me"]);
});

Route::group([
    'middleware' => 'api',

], function () {
    Route::post('pay',[paymentController::class, "start"]);
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
Route::post("category/get-by-level", [CategoryController::class, "getByLevel"]);
Route::post("category/toggle-active/{id}", [CategoryController::class, "toggleActive"]);
Route::get("category/{id}/getProperties", [CategoryController::class, "getProperties"]);
Route::get("category/{id}/updateProperties", [CategoryController::class, "updateProperties"]);
Route::get("categories", [GlobalController::class, "getAllCategory"]);
Route::get("product/search", [ProductController::class, "search"]);
Route::post("product/search", [ProductController::class, "search"]);
Route::post("getBrands", [GlobalController::class, "getBrands"]);
Route::get("getAllBrands", [GlobalController::class, "getAllBrands"]);
Route::get("getAllProperties", [GlobalController::class, "getAllProperties"]);


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

//comment route
Route::get("comment/all", [CommentController::class, "getAll"]);
Route::middleware('jwt.verify')->prefix('comment/')->group(function() {
    Route::get("{id}", [CommentController::class, "get"]);
    Route::post("", [CommentController::class, "store"]);
    Route::delete("{id}", [CommentController::class, "delete"]);
    Route::put("{id}", [CommentController::class, "update"]);
});


//brand route
Route::middleware('jwt.verify')->prefix('brand/')->group(function() {
    Route::post("", [BrandController::class, "store"]);
    Route::put("{id}", [BrandController::class, "update"]);
});

//fields route
Route::middleware('jwt.verify')->prefix('fields/')->group(function() {
    Route::post("", [FieldController::class, "create"]);
    Route::get("", [FieldController::class, "list"]);
    Route::put("{id}", [FieldController::class, "update"]);
    Route::delete("{id}", [FieldController::class, "delete"]);
});
Route::get("fields/cate-fields/list", [FieldController::class, "catFieldsList"]);
//category fields route
Route::middleware('jwt.verify')->prefix('fields/')->group(function() {
    Route::get("cate-fields/list/{id}", [FieldController::class, "catFields"]);
    
    Route::post("cate-fields", [FieldController::class, "storeCatFields"]);
    Route::delete("cate-fields/{id}", [FieldController::class, "deleteCatFields"]);
});

//cart route
Route::middleware('jwt.verify')->prefix('cart/')->group(function() {
    Route::get("", [CartController::class, "getCart"]);
    Route::post("", [CartController::class, "addToCard"]);
    Route::post("group", [CartController::class, "addAllToCard"]);
    Route::put("{id}", [CartController::class, "update"]);
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
    // Route::prefix('category/')->group(function() {
    Route::post("", [CategoryController::class, "store"]);
    Route::put("{id}", [CategoryController::class, "update"]);
    Route::delete("{id}", [CategoryController::class, "delete"]);

    Route::post("addProperties", [CategoryController::class, "addProperties"]);
    // Route::post("updateProperties", [CategoryController::class, "updateProperties"]);
    
    Route::post("updateProperties", [CategoryController::class, "updateProperties"]);
    Route::post("allProperties", [PropertiesController::class, "allProperties"]);
    Route::post("withParent", [CategoryController::class, "withParent"]);
});

Route::post("banners/upload", [BannerController::class, "upload"]);
//banners route
Route::middleware('jwt.verify')->prefix('banners/')->group(function() {
    // Route::prefix('category/')->group(function() {
    Route::get("", [BannerController::class, "index"]);
    Route::get("{id}", [BannerController::class, "show"]);
    Route::post("", [BannerController::class, "store"]);
    Route::put("{id}", [BannerController::class, "update"]);
    Route::delete("{id}", [BannerController::class, "delete"]);
    

    
});

//product route
Route::post('product/image/upload', [ImageController::class, "upload"])->name('image.upload');
Route::post('category/image/upload', [CategoryController::class, "upload"])->name('category.image.upload');
Route::middleware('jwt.verify')->prefix('product/')->group(function() {
    Route::post("", [ProductController::class, "store"]);
    Route::put("{id}", [ProductController::class, "update"]);
    Route::delete("{id}", [ProductController::class, "delete"]);
    Route::post("adminSearch", [ProductController::class, "search"]);
    Route::post("product_properties", [ProductController::class, "product_properties"]);
    Route::get("get_properties/{id}", [ProductController::class, "get_properties"]);

//    Route::post('image/upload', [ImageController::class, "upload"])->name('image.upload');
    Route::delete('image/{id}', [ImageController::class, "destroy"])->name('image.delete');
//    Route::post("addProperties", [ProductController::class, "addProperties"]);
});

Route::middleware('jwt.verify')->prefix('properties/')->group(function() {
    Route::get("", [PropertiesController::class, "list"]);
    Route::post("", [PropertiesController::class, "save"]);
});