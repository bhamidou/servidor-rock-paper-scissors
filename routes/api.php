<?php

use App\Http\Controllers\API\AuthController;
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
    Route::controller(ControllerUsuario::class)->middleware(['auth:sanctum','admin'])->group( function () {

        Route::prefix('user')->group(function (){
            Route::get('/','index');
            Route::get('/{id}','show');
            Route::put('/{id}','update');
            Route::post('/','store');
            Route::delete('/{id}','destroy');
        });

        Route::prefix('partida')->group(function (){
            Route::get('/','index');
            Route::get('/{id}','show');
            Route::put('/{id}','update');
            Route::post('/','store');
            Route::delete('/{id}','destroy');
        });
        Route::prefix('ronda')->group(function (){
            Route::get('/','index');
            Route::get('/{id}','show');
            Route::put('/{id}','update');
            Route::post('/','store');
            Route::delete('/{id}','destroy');
        });
    });
});

//un usuario solo podrá crear nuevas partidas, si el controlador se le permite

Route::controller(ControllerPartida::class)->middleware(['auth:sanctum','jugar'])->group( function () {
    Route::prefix('partida')->group(function (){
        Route::get('/','index');
        Route::get('/{id}','show');
        Route::post('/','store');
    });
});

Route::controller(ControllerRonda::class)->middleware(['auth:sanctum','jugar'])->group( function () {
    Route::prefix('ronda')->group(function (){
        Route::get('/{id}','show');
        Route::post('/','store');
    });
});

Route::controller(ControllerUsuario::class)->middleware(['auth:sanctum','jugar'])->group( function () {
    Route::prefix('user')->group(function (){
        Route::post('/','store');
        Route::get('/','index');
        Route::get('/{id}','show');
    });
});

Route::controller(AuthController::class)->group(function(){
    Route::post('/signup','signup');
    Route::get('/login','login');
    Route::get('/logout','logout');

});

Route::get('', function () {
    return response()->json("Unauthorized",401);
})->name('nologin');
