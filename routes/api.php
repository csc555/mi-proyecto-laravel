<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['cors']], function () {
    Route::post('register', [UserController::class,'register'])
        ->name('register.register');
    Route::post('login', [UserController::class,'login'])
        ->name('login.login');

    Route::get('mostrar', [UserController::class,'mostrar'])
        ->name('mostrar.mostrar');

    Route::post('actualizar/{id}', [UserController::class,'actualizar'])
        ->name('actualizar.actualizar');

    Route::get('eliminar/{id}', [UserController::class,'eliminar'])
        ->name('eliminar.eliminar');
});
