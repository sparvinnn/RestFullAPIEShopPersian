<?php

use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\ProductController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});



Route::prefix('v1')->namespace('Api\v1')->group(function (){
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('categories/{category}', [CategoryController::class, 'single']);
    Route::post('/categories/create', [CategoryController::class, 'store']);
    Route::post('/products/create/{id}', [ProductController::class, 'store']);

    Route::post('login', [UserController::class, 'login']);
    Route::post('register', [UserController::class, 'register']);

    Route::group(['middleware' => 'auth:api'], function(){
        Route::post('users', [UserController::class, 'users']);

    });

});


