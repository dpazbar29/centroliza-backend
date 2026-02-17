<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Centro;
use App\Models\Etapa;
use App\Models\Curso;
use App\Models\Asignatura;
use App\Models\Grupo;
use App\Models\User;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    public function index(Centro $centro, Etapa $etapa, Curso $curso, Asignatura $asignatura)
    {
        if ($asignatura->curso_id !== $curso->id || $curso->etapa->centro_id !== $centro->id) {
            abort(404);
        }

        $grupos = $asignatura->grupos()->withCount(['matriculas as alumnos_count', 'asistencias'])->with('profesorTutor:id,name')->orderBy('nombre_grupo')->get();

        return response()->json($grupos);
    }

    public function store(Request $request, Centro $centro, Etapa $etapa, Curso $curso, Asignatura $asignatura)
    {
        if ($asignatura->curso_id !== $curso->id || $curso->etapa->centro_id !== $centro->id) {
            abort(403);
        }

        $data = $request->validate([
            'nombre_grupo' => 'required|string|max:20|unique:grupos,nombre_grupo,NULL,id,curso_id,' . $curso->id . ',asignatura_id,' . $asignatura->id,
            'profesor_tutor_id' => 'nullable|exists:usuarios,id',
            'capacidad_maxima' => 'integer|min:1|max:50',
        ]);

        $grupo = $asignatura->grupos()->create(array_merge($data, [
            'curso_id' => $curso->id,
        ]));

        return response()->json($grupo->load(['profesorTutor', 'matriculas.alumno']), 201);
    }

    public function show(Centro $centro, Etapa $etapa, Curso $curso, Asignatura $asignatura, Grupo $grupo)
    {
        if ($grupo->asignatura_id !== $asignatura->id || $asignatura->curso_id !== $curso->id || $curso->etapa->centro_id !== $centro->id) {
            abort(404);
        }

        $grupo->load(['profesorTutor', 'matriculas.alumno', 'asistencias.alumno']);
        return response()->json($grupo);
    }

    public function update(Request $request, Centro $centro, Etapa $etapa, Curso $curso, Asignatura $asignatura, Grupo $grupo)
    {
        if ($grupo->asignatura_id !== $asignatura->id || $asignatura->curso_id !== $curso->id || $curso->etapa->centro_id !== $centro->id) {
            abort(403);
        }

        $data = $request->validate([
            'nombre_grupo' => 'sometimes|string|max:20|unique:grupos,nombre_grupo,' . $grupo->id . ',id,curso_id,' . $curso->id . ',asignatura_id,' . $asignatura->id,
            'profesor_tutor_id' => 'nullable|exists:usuarios,id',
            'capacidad_maxima' => 'sometimes|integer|min:1|max:50',
        ]);

        $grupo->update($data);
        return response()->json($grupo->fresh()->load(['profesorTutor', 'matriculas']));
    }

    public function destroy(Centro $centro, Etapa $etapa, Curso $curso, Asignatura $asignatura, Grupo $grupo)
    {
        if ($grupo->asignatura_id !== $asignatura->id || $asignatura->curso_id !== $curso->id || $curso->etapa->centro_id !== $centro->id) {
            abort(403);
        }

        $grupo->delete();
        return response()->json(['message' => 'Grupo eliminado']);
    }
}
