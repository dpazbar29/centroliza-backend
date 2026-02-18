<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Centro;
use App\Models\Etapa;
use App\Models\Curso;
use App\Models\Grupo;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MatriculaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Centro $centro, Etapa $etapa, Curso $curso)
    {
        if ($curso->etapa_id !== $etapa->id || $etapa->centro_id !== $centro->id) {
            abort(404);
        }

        $matriculas = Matricula::with([
                'alumno:id,name,email,dni', 
                'tutor:id,name', 
                'grupo.profesorTutor:id,name', 
                'grupo.asignatura:id,nombre'
            ])
            ->where('curso_id', $curso->id)
            ->orderBy('fecha_matricula', 'desc')
            ->get();

        return response()->json($matriculas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Centro $centro, Etapa $etapa, Curso $curso)
    {
        if ($curso->etapa_id !== $etapa->id || $etapa->centro_id !== $centro->id) {
            abort(403);
        }

        $data = $request->validate([
            'alumno_id' => [
                'required',
                'exists:usuarios,id',
                Rule::unique('matriculas')->where(fn($query) => $query->where('curso_id', $curso->id))
            ],
            'grupo_id' => 'required|exists:grupos,id',
            'fecha_matricula' => 'nullable|date|after_or_equal:today',
        ]);

        $grupo = Grupo::findOrFail($data['grupo_id']);
        if ($grupo->curso_id !== $curso->id) {
            return response()->json(['message' => 'El grupo no pertenece a este curso'], 422);
        }

        $matricula = Matricula::create([
            'alumno_id' => $data['alumno_id'],
            'curso_id' => $curso->id,
            'grupo_id' => $grupo->id,
            'tutor_id' => $grupo->profesor_tutor_id,
            'estado' => 'activa',
            'fecha_matricula' => $data['fecha_matricula'] ?? now()->toDateString(),
        ]);

        return response()->json(
            $matricula->load(['alumno', 'tutor', 'grupo.asignatura', 'grupo.profesorTutor']),201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Centro $centro, Etapa $etapa, Curso $curso, Matricula $matricula)
    {
        if ($matricula->curso_id !== $curso->id || $curso->etapa_id !== $etapa->id || $etapa->centro_id !== $centro->id) {
            abort(404);
        }

        return response()->json(
            $matricula->load(['alumno', 'tutor', 'grupo.asignatura'])
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Centro $centro, Etapa $etapa, Curso $curso, Matricula $matricula)
    {
        if ($matricula->curso_id !== $curso->id || $curso->etapa_id !== $etapa->id || $etapa->centro_id !== $centro->id) {
            abort(403);
        }

        $data = $request->validate([
            'grupo_id' => [
                'sometimes',
                'exists:grupos,id',
                fn($attribute, $value, $fail) => Grupo::find($value)?->curso_id !== $curso->id ? $fail('El grupo no pertenece a este curso') : null
            ],
            'tutor_id' => 'sometimes|nullable|exists:usuarios,id',
            'estado' => 'sometimes|in:activa,suspendida,baja',
            'fecha_baja' => 'nullable|date|after:fecha_matricula',
        ]);

        $matricula->update($data);
        return response()->json(
            $matricula->fresh()->load(['alumno', 'tutor', 'grupo.asignatura'])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Centro $centro, Etapa $etapa, Curso $curso, Matricula $matricula)
    {
        if ($matricula->curso_id !== $curso->id || $curso->etapa_id !== $etapa->id || $etapa->centro_id !== $centro->id) {
            abort(403);
        }

        $matricula->delete();
        return response()->json(['message' => 'Matrícula eliminada correctamente']);
    }
}
