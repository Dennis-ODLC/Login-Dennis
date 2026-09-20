<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\LoginController;

Route::controller(LoginController::class)->group(function () {

    Route::get('/login', 'verLogin');
    Route::post('/login', 'entrar');

    Route::get('/login/registrar', 'verRegistro');
    Route::post('/login/registrar', 'crear');

    Route::get('/login/recuperar', 'verRecuperar');
    Route::post('/login/recuperar', 'codigo');

    Route::get('/login/validar', 'verCodigo');
    Route::post('/login/validar', 'comprobar');

    Route::get('/login/passwordd', 'verPassword');
    Route::post('/login/passwordd', 'cambiarPass');

});