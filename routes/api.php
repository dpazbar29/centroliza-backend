<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EtapaController;
use App\Http\Controllers\Api\CursoController;
use App\Http\Controllers\Api\AsignaturaController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // CENTROS
    Route::apiResource('centros', \App\Http\Controllers\Api\CentroController::class)->only(['index', 'show']);
    
    // ETAPAS ANIDADAS EN CENTRO
    Route::apiResource('centros.etapas', EtapaController::class);

    // CURSOS DE CADA ETAPA
    Route::apiResource('centros.etapas.cursos', CursoController::class);

    // ASIGNATURAS DE CADA CURSO
    Route::apiResource('centros.etapas.cursos.asignaturas', AsignaturaController::class);
});
