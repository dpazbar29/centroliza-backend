<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\Centro;
use App\Models\Etapa;
use App\Models\Curso;
use Illuminate\Http\Request;

class AsignaturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Centro $centro, Etapa $etapa, Curso $curso)
{
    if ($curso->etapa->centro_id !== $centro->id) abort(404);
    
    $asignaturas = $curso->asignaturas()
        ->with('grupos.profesorTutor')->orderBy('horas_semanales', 'desc')->get(['id', 'nombre', 'codigo', 'horas_semanales', 'tipo']);
        
    return response()->json($asignaturas);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Centro $centro, Etapa $etapa, Curso $curso)
    {
        if ($curso->etapa->centro_id !== $centro->id) abort(403);

        $data = $request->validate([
            'nombre' => 'required|string|max:100|unique:asignaturas,nombre,NULL,id,curso_id,' . $curso->id,
            'codigo' => 'nullable|string|max:20|unique:asignaturas',
            'horas_semanales' => 'required|integer|min:1|max:12',
            'tipo' => 'sometimes|in:troncal,especifica,optativa',
        ]);

        $asignatura = $curso->asignaturas()->create($data);
        return response()->json($asignatura->load('profesores'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Centro $centro, Etapa $etapa, Curso $curso, Asignatura $asignatura)
    {
        if ($asignatura->curso_id !== $curso->id || $curso->etapa->centro_id !== $centro->id) abort(404);
        return response()->json($asignatura->load(['profesores', 'grupos']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Centro $centro, Etapa $etapa, Curso $curso, Asignatura $asignatura)
    {
        if ($asignatura->curso_id !== $curso->id || $curso->etapa->centro_id !== $centro->id) abort(403);

        $data = $request->validate([
            'nombre' => 'sometimes|string|max:100|unique:asignaturas,nombre,' . $asignatura->id . ',id,curso_id,' . $curso->id,
            'codigo' => 'sometimes|string|max:20|unique:asignaturas,codigo,' . $asignatura->id,
            'horas_semanales' => 'sometimes|integer|min:1|max:12',
            'tipo' => 'sometimes|in:troncal,especifica,optativa',
        ]);

        $asignatura->update($data);
        return response()->json($asignatura->fresh()->load('profesores'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Centro $centro, Etapa $etapa, Curso $curso, Asignatura $asignatura)
    {
        if ($asignatura->curso_id !== $curso->id || $curso->etapa->centro_id !== $centro->id) abort(403);
        $asignatura->delete();
        return response()->json(['message' => 'Asignatura eliminada']);
    }
}
