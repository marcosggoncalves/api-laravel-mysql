<?php

use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\TarefasController;
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

Route::group(['prefix' => 'v1'], function () {
    Route::apiResources([
        'usuarios' => UsuariosController::class,
        'tarefas' => TarefasController::class
    ]);
});

Route::post('/v1/usuarios/login', [UsuariosController::class, 'login']);