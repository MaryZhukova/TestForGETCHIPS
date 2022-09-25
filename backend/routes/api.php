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
    Route::post('/login', '\App\Http\Controllers\Api\V1\AuthController@login');
    Route::post('/register', '\App\Http\Controllers\Api\V1\AuthController@register');
    Route::get('/adverts', '\App\Http\Controllers\Api\V1\AdvertController@index');

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/logout', '\App\Http\Controllers\Api\V1\AuthController@logout');
        Route::get('/adverts/{id}', '\App\Http\Controllers\Api\V1\AdvertController@show');
        Route::delete('/adverts/{id}', '\App\Http\Controllers\Api\V1\AdvertController@delete');
        Route::patch('/adverts/{id}/', '\App\Http\Controllers\Api\V1\AdvertController@update');
        Route::delete('/adverts/{id}/', '\App\Http\Controllers\Api\V1\AdvertController@delete');
        Route::post('/adverts', '\App\Http\Controllers\Api\V1\AdvertController@store');

    });
});
