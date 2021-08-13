<?php

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
Route::post('/register', 'Api\AuthController@register');
Route::post('/login', 'Api\AuthController@login');
Route::post('/reset-password', 'Api\AuthController@resetPassword');

Route::middleware(['auth:api', 'verified'])->group(function () {
    Route::get('/suppliers', 'Api\SupplierController@index');
    Route::get('/suppliers/{id}/products', 'Api\ProductController@index');
    Route::post('/request/{id}', 'Api\ProductController@storeRequest');
    Route::get('/my-product', 'Api\ProductController@myProduct');
});

