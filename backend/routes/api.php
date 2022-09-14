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


// User
Route::post('/login', '\App\Http\Controllers\API\AuthController@login');
Route::post('/logout', '\App\Http\Controllers\API\AuthController@logout');
Route::post('/create', '\App\Http\Controllers\API\AuthController@create');
Route::get('/ussers', '\App\Http\Controllers\API\UserController@list');


Route::get('/adverts', '\App\Http\Controllers\API\AdvertController@index');
Route::post('/adverts', '\App\Http\Controllers\API\AdvertController@store');



//Route::get('/adverts/{id}', '\App\Http\Controllers\API\AdvertController@show')->middleware('auth:sanctum');
Route::get('/adverts/{id}', '\App\Http\Controllers\API\AdvertController@show');
//Route::delete('adverts/{id}', '\App\Http\Controllers\API\AdvertController@delete')->name('advert.delete');
