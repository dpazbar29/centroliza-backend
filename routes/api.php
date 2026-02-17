<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EtapaController;
use App\Http\Controllers\Api\CursoController;
use App\Http\Controllers\Api\AsignaturaController;
use App\Http\Controllers\Api\ProfesorController;
use App\Http\Controllers\Api\ProfesorAsignaturaController;
use App\Http\Controllers\Api\AlumnoController;
use App\Http\Controllers\Api\MatriculaController;
use App\Http\Controllers\Api\CentroController;
use App\Http\Controllers\Api\RolJerarquiaController;
use App\Http\Controllers\Api\AsistenciaController;
use App\Http\Controllers\Api\GrupoController;
use App\Http\Controllers\Api\EvaluacionController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // CENTROS
    Route::apiResource('centros', CentroController::class)->only(['index', 'show', 'store']);
    
    // ETAPAS ANIDADAS EN CENTRO
    Route::apiResource('centros.etapas', EtapaController::class);

    // CURSOS DE CADA ETAPA
    Route::apiResource('centros.etapas.cursos', CursoController::class);

    // ASIGNATURAS DE CADA CURSO
    Route::apiResource('centros.etapas.cursos.asignaturas', AsignaturaController::class);

    // PROFESORES DEL CENTRO
    Route::apiResource('centros.profesores', ProfesorController::class)->parameters(['profesores' => 'profesor']);

    // PROFESORES DE CADA ASIGNATURA
    Route::apiResource('centros.etapas.cursos.asignaturas.profesores', ProfesorAsignaturaController::class)->parameters(['profesores' => 'profesor']);

    // ALUMNOS DEL CENTRO
    Route::apiResource('centros.alumnos', AlumnoController::class)->only(['index']);

    // ALUMNOS POR CURSO
    Route::apiResource('centros.etapas.cursos.matriculas', MatriculaController::class)->only(['index']);

    // GRUPOS
    Route::apiResource('centros.etapas.cursos.asignaturas.grupos', GrupoController::class);
    
    // ASISTENCIAS
    Route::apiResource('centros.etapas.cursos.asignaturas.grupos.asistencias', AsistenciaController::class);

    // EVALUACIONES
    Route::apiResource('centros.etapas.cursos.asignaturas.grupos.evaluaciones', EvaluacionController::class);

    // AVISOS
    Route::apiResource('centros.avisos', AvisoController::class);

    // ROLES JERARQUIA
    Route::apiResource('centros.roles-jerarquia', RolJerarquiaController::class);

    // UNIRSE A CENTRO
    Route::post('centros/{centro}/unirse', [CentroController::class, 'unirse']);
});
