<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Centro;
use App\Models\Etapa;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Centro $centro, Etapa $etapa)
    {
        if ($etapa->centro_id !== $centro->id) abort(404);
        $cursos = $etapa->cursos()->withCount('asignaturas')->orderBy('ano_academico')->get(['id', 'nombre', 'codigo_curso', 'ano_academico']);
        return response()->json($cursos);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Centro $centro, Etapa $etapa)
    {
        if ($etapa->centro_id !== $centro->id) abort(403);

        $data = $request->validate([
            'nombre' => 'required|string|max:100|unique:cursos,nombre,NULL,id,etapa_id,' . $etapa->id,
            'codigo_curso' => 'nullable|string|max:20|unique:cursos',
            'ano_academico' => 'required|integer|min:2000|max:2030',
        ]);

        $curso = $etapa->cursos()->create($data);
        return response()->json($curso->load('asignaturas'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Centro $centro, Etapa $etapa, Curso $curso)
    {
        if ($curso->etapa->centro_id !== $centro->id) abort(404);
        return response()->json($curso->load(['asignaturas.profesores', 'matriculas.alumno']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Centro $centro, Etapa $etapa, Curso $curso)
    {
        if ($curso->etapa_id !== $etapa->id || $etapa->centro_id !== $centro->id) abort(403);

        $data = $request->validate([
            'nombre' => 'sometimes|string|max:100|unique:cursos,nombre,' . $curso->id . ',id,etapa_id,' . $etapa->id,
            'codigo_curso' => 'sometimes|string|max:20|unique:cursos,codigo_curso,' . $curso->id,
            'ano_academico' => 'sometimes|integer|min:2000|max:2030',
        ]);

        $curso->update($data);
        return response()->json($curso->fresh()->load('asignaturas'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Centro $centro, Etapa $etapa, Curso $curso)
    {
        if ($curso->etapa_id !== $etapa->id || $etapa->centro_id !== $centro->id) abort(403);
        $curso->delete();
        return response()->json(['message' => 'Curso eliminado']);
    }
}
