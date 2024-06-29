<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'App\Http\Controllers'], function(){
    Route::apiResource('/users', UserController::class);
    Route::post('/login', ['uses' => 'UserController@login']);
});
Route::group(['namespace'=>'App\Http\Controllers', 'middleware' => 'auth:sanctum'], function(){
    Route::get('/me', ['uses' => 'UserController@me']);
    Route::get('/categories', ['uses' => 'CategoryController@index']);
    Route::apiResource('/match', MatchController::class);
    Route::get('/avaible',['uses'=>'MatchController@getAvaibleMatches']);
});
