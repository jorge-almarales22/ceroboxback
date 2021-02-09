<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login','AuthController@login');
    Route::post('register','AuthController@register');
    Route::group(['middleware' => ['jwt.auth']], function () {
        Route::post('refresh','AuthController@refreshToken');
        Route::get('expire','AuthController@expireToken');
    });
});

Route::group(['prefix' => 'auth'], function () {
    Route::group(['middleware' => ['jwt.auth']], function () {
        Route::post('clientes','ControllerCliente@store');
        Route::get('clientes','ControllerCliente@index');
        Route::put('clientes/{id}','ControllerCliente@update');
        Route::delete('clientes/{id}','ControllerCliente@destroy');
    });
});

Route::group(['prefix' => 'auth'], function () {
    Route::group(['middleware' => ['jwt.auth']], function () {
        Route::post('servicios','ControllerServicio@store');
        Route::get('servicios','ControllerServicio@index');
        Route::get('servicios/{id}','ControllerServicio@serviciosXuser');
        Route::put('servicios/{id}','ControllerServicio@update');
        Route::delete('servicios/{id}','ControllerServicio@destroy');
    });
});