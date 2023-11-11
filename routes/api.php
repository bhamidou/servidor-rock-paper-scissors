<?php

use App\Http\Controllers\ControllerPartida;
use App\Http\Controllers\ControllerRonda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControllerUsuario;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::prefix('admin')->group( function ()  {
    Route::controller(ControllerUsuario::class)->group( function () {
        Route::prefix('user')->group(function (){
            Route::get('/','index');
            Route::get('/{id}','show');
            Route::put('/{id}','update');
            Route::post('/','store');
            Route::delete('/{id}','destroy');
        });
    });
});

Route::controller(ControllerPartida::class)->group( function () {
    Route::prefix('partida')->group(function (){
        Route::get('/','index');
        Route::get('/{id}','show');
        Route::put('/{id}','update');
        Route::post('/','store');
        Route::delete('/{id}','destroy');
    });
});

Route::controller(ControllerRonda::class)->group( function () {
    Route::prefix('ronda')->group(function (){
        Route::get('/','index');
        Route::get('/{id}','show');
        Route::put('/{id}','update');
        Route::post('/','store');
        Route::delete('/{id}','destroy');
    });
});
