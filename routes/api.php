<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // TEST ROLE (borra después)
    Route::middleware('role:profesor')->get('/admin-only', fn() => 
        response()->json(['message' => 'Profesor OK']));
    
    // CENTROS
    Route::apiResource('centros', \App\Http\Controllers\Api\CentroController::class)
         ->only(['index', 'show']);
});
