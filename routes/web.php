<?php

use Illuminate\Support\Facades\Auth;
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
Auth::routes(['verify' => true]);

Route::get('/', function () {
    if(Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('login');
})->name('main-login');

Route::get('/verifymobile', 'Api\AuthController@verifyEmail')->name('verify-mobile');

Route::middleware(['verified'])->group(function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    // ====== SUPPLIER PRODUCTS =========//
    Route::get('/product/datatable', 'ProductController@datatable')->name('product.datatable');
    Route::post('/product/store', 'ProductController@store')->name('product.store');
    Route::get('/product/edit/{id?}', 'ProductController@edit')->name('product.edit');
    Route::put('/product/update/{id?}', 'ProductController@update')->name('product.update');
    Route::delete('/product/delete/{id?}', 'ProductController@delete')->name('product.delete');
    Route::get('/product/{id}', 'ProductController@show')->name('product.show');

    // ===== SUPPLIER PRODUCT REQUEST ======= //
    Route::get('/product-request', 'RequestProductSupplierController@index')->name('product-request.index');
    Route::get('/product-request/datatable', 'RequestProductSupplierController@datatable')->name('product-request.datatable');
    Route::get('/product-request/detail/{id?}', 'RequestProductSupplierController@detailProduct')->name('product-request.detail');
    Route::put('/product-request/approve/{id?}', 'RequestProductSupplierController@approve')->name('product-request.approve');
});


Route::get('/home', 'HomeController@index')->name('home');
