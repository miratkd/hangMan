<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'App\Http\Controllers'], function(){
    Route::apiResource('/users', UserController::class);
    Route::get('/me', ['uses' => 'UserController@me'])->middleware(['auth:sanctum']);
    Route::post('/login', ['uses' => 'UserController@login']);
    // Route::post('/login', ['uses' => 'LoginController@login']);
});
