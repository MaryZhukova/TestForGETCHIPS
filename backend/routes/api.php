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
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/


// User
Route::post('/login', '\App\Http\Controllers\API\AuthController@login');
Route::post('/logout', '\App\Http\Controllers\API\AuthController@logout');
Route::post('/create', '\App\Http\Controllers\API\AuthController@create');

Route::get('adverts', '\App\Http\Controllers\API\AdvertController@index')->name('advert.index');
Route::put('adverts/{id}', '\App\Http\Controllers\API\AdvertController@update')->name('advert.update');
Route::delete('adverts/{id}', '\App\Http\Controllers\API\AdvertController@delete')->name('advert.delete');
