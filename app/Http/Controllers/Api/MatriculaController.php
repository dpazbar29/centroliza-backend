<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Centro;
use App\Models\Etapa;
use App\Models\Curso;
use App\Models\Matricula;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Centro $centro, Etapa $etapa, Curso $curso)
    {
        if ($curso->etapa->centro_id !== $centro->id) abort(404);
        
        $alumnos = $curso->usuarios()
            ->wherePivot('estado', 'activa')
            ->orderBy('name')
            ->get(['usuarios.id', 'usuarios.name', 'usuarios.email', 'usuarios.dni']);
            
        return response()->json($alumnos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Centro $centro, Etapa $etapa, Curso $curso)
    {
        if ($curso->etapa->centro_id !== $centro->id) abort(403);

        $data = $request->validate([
            'alumno_id' => 'required|exists:usuarios,id|unique:matriculas,alumno_id,NULL,id,curso_id,' . $curso->id,
            'tutor_id' => 'nullable|exists:usuarios,id',
            'fecha_matricula' => 'nullable|date|after_or_equal:today',
        ]);

        $matricula = Matricula::create([
            'alumno_id' => $data['alumno_id'],
            'curso_id' => $curso->id,
            'tutor_id' => $data['tutor_id'],
            'estado' => 'activa',
            'fecha_matricula' => $data['fecha_matricula'] ?? now(),
        ]);

        return response()->json($matricula->load(['alumno', 'tutor']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Centro $centro, Etapa $etapa, Curso $curso, Matricula $matricula)
    {
        if ($matricula->curso_id !== $curso->id || $curso->etapa->centro_id !== $centro->id) {
            abort(404);
        }
        
        return response()->json($matricula->load(['alumno', 'tutor', 'curso']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Centro $centro, Etapa $etapa, Curso $curso, Matricula $matricula)
    {
        if ($matricula->curso_id !== $curso->id || $curso->etapa->centro_id !== $centro->id) {
            abort(403);
        }

        $data = $request->validate([
            'tutor_id' => 'sometimes|exists:usuarios,id',
            'estado' => 'sometimes|in:activa,suspendida,baja',
            'fecha_baja' => 'sometimes|date|after:fecha_matricula',
        ]);

        $matricula->update($data);
        return response()->json($matricula->fresh()->load(['alumno', 'tutor']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Centro $centro, Etapa $etapa, Curso $curso, Matricula $matricula)
    {
        if ($matricula->curso_id !== $curso->id || $curso->etapa->centro_id !== $centro->id) {
            abort(403);
        }
        
        $matricula->delete();
        return response()->json(['message' => 'Matrícula eliminada']);
    }
}
