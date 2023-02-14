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

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ReserveController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* ================================================================================= */

Route::post('soli', [ReserveController::class, "storeSolicitud"]);


/* ================================================================================= */


Route::prefix("v1/auth")->group(function () {

    Route::post('/login', [AuthController::class, "login"]);
    Route::post('/token-verify', [AuthController::class, "tokenVerify"]);   // PRUEBAAA me

    // TODO: EL REGISTRO DEBERIA HACERLO EL ADMINISTRADOR
    Route::post('/registro', [AuthController::class, "registro"]);



    Route::middleware("auth:sanctum")->group(function () {
        Route::get('/perfil', [AuthController::class, "perfil"]);
        Route::post('/logout', [AuthController::class, "logout"]);
    });
});

Route::middleware("auth:sanctum")->group(function () {

    Route::apiResource("solicitud", ReserveController::class);
    Route::apiResource("horario", HorarioController::class);
});