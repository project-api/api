<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// Set Route for Category

Route::resource('categories', 'CategoryController',['except' =>['create', 'edit']]);

// Set Route for Product
Route::resource('products', 'ProductController',['except' =>['create', 'edit']]);

// Set Route for Login action

Route::post('/login', 'Auth\LoginController@authenticate');

// Set Route for register action
Route::post('/register', 'Auth\RegisterController@register');

// Set Route for logout action
Route::post('/logout', 'Auth\LogoutController');
