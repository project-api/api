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
// Route::group(['prefix' => 'categories'], function() {
//   // Route::get('/', 'CategoryController@index');
//   // Route::get('/{id}', 'CategoryController@show')->where('id', '[0-9]+');
//   // Route::post('/', 'CategoryController@store');
//
// });
Route::resource('categories', 'CategoryController',['except' =>['create', 'edit']]);
