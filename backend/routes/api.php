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
Route::prefix('v1')->group(function () {
    Route::post('/login', '\App\Http\Controllers\API\AuthController@login');
    Route::get('/adverts', '\App\Http\Controllers\API\AdvertController@index');

    Route::group(['middleware' => ['auth:sanctum']], function() {
        Route::get('/adverts/{id}', '\App\Http\Controllers\API\AdvertController@show');
        Route::delete('/adverts/{id}', '\App\Http\Controllers\API\AdvertController@delete');
        Route::patch('/adverts/{id}/', '\App\Http\Controllers\API\AdvertController@update');
        Route::delete('/adverts/{id}/', '\App\Http\Controllers\API\AdvertController@delete');
        Route::post('/adverts', '\App\Http\Controllers\API\AdvertController@store');
        Route::post('/logout', '\App\Http\Controllers\API\AdvertController@logout');
    });


});



// User
//Route::post('/create', '\App\Http\Controllers\API\AuthController@create');
//Route::get('/users', '\App\Http\Controllers\API\UserController@list');



;
