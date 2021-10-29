<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Controllers\testController;
use App\Http\Controllers\paymentController;
Route::get('/', function () {
    return view('welcome');
});
Route::get('test',function(){
    return view('test');
});

Route::get('order',[paymentController::class, "order"]);
Route::post('shop',[testController::class, "add_order"]);
Route::post('start',[testController::class, "test"]);
