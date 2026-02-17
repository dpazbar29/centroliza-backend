<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\Centro;
use App\Models\Etapa;
use App\Models\Curso;
use Illuminate\Http\Request;
use App\Models\ProfesorAsignatura; 

class ProfesorAsignaturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Centro $centro, Etapa $etapa, Curso $curso, Asignatura $asignatura)
    {
        $profesores = $asignatura->profesores()->select('usuarios.id', 'usuarios.name', 'usuarios.email', 'usuarios.dni')->where('usuarios.role', 'profesor')->get();
        return response()->json($profesores);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Centro $centro, Etapa $etapa, Curso $curso, Asignatura $asignatura)
    {
        if ($asignatura->curso_id !== $curso->id || $curso->etapa->centro_id !== $centro->id) {
            abort(403);
        }

        $data = $request->validate([
            'profesor_id' => 'required|exists:usuarios,id',
            'ano_academico' => 'sometimes|integer|min:2000|max:2030',
            'horas_asignadas' => 'sometimes|integer|min:0|max:12'
        ]);

        $exists = ProfesorAsignatura::where('profesor_id', $data['profesor_id'])->where('asignatura_id', $asignatura->id)->exists();

        if ($exists) {
            return response()->json(['message' => 'Profesor ya asignado'], 422);
        }

        $profesorAsignatura = ProfesorAsignatura::create([
            'profesor_id' => $data['profesor_id'],
            'asignatura_id' => $asignatura->id,
            'ano_academico' => $data['ano_academico'] ?? now()->year,
            'horas_asignadas' => $data['horas_asignadas'] ?? 0,
        ]);

        return response()->json($profesorAsignatura->load('profesor'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Centro $centro, Etapa $etapa, Curso $curso, Asignatura $asignatura, $profesorId)
    {
        if ($asignatura->curso_id !== $curso->id || $curso->etapa->centro_id !== $centro->id) {
            abort(403);
        }

        ProfesorAsignatura::where('asignatura_id', $asignatura->id)->where('profesor_id', $profesorId)->delete();

        return response()->json(['message' => 'Profesor desasignado correctamente']);
    }
}
