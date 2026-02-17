<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use App\Models\User;
use App\Models\Evaluacion;
use Illuminate\Http\Request;

class EvaluacionController extends Controller
{
    public function index(Grupo $grupo)
    {
        if ($grupo->curso->etapa->centro_id !== auth()->user()->centro_id) {
            abort(403);
        }

        $evaluaciones = $grupo->evaluaciones()
            ->with(['alumno:id,name', 'asignatura:id,nombre'])->orderBy('trimestre')->orderBy('nota', 'desc')->get();

        return response()->json($evaluaciones);
    }

    public function store(Request $request, Grupo $grupo)
    {
        if ($grupo->curso->etapa->centro_id !== auth()->user()->centro_id) {
            abort(403);
        }

        $data = $request->validate([
            'alumno_id' => 'required|exists:usuarios,id',
            'nota' => 'required|numeric|min:0|max:10',
            'trimestre' => 'required|in:1,2,3',
            'fecha' => 'required|date|after_or_equal:2025-01-01',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        $matricula = $grupo->matriculas()->where('alumno_id', $data['alumno_id'])->where('estado', 'activa')->firstOrFail();

        $existente = Evaluacion::where('grupo_id', $grupo->id)->where('alumno_id', $data['alumno_id'])->where('trimestre', $data['trimestre'])->first();

        if ($existente) {
            return response()->json(['error' => 'Evaluación ya existe para este trimestre'], 422);
        }

        $evaluacion = $grupo->evaluaciones()->create(array_merge($data, [
            'asignatura_id' => $grupo->asignatura_id,
        ]));

        return response()->json($evaluacion->load(['alumno', 'asignatura']), 201);
    }

    public function show(Grupo $grupo, Evaluacion $evaluacion)
    {
        if ($evaluacion->grupo_id !== $grupo->id || $grupo->curso->etapa->centro_id !== auth()->user()->centro_id) {
            abort(404);
        }

        return response()->json($evaluacion->load(['alumno', 'asignatura', 'grupo']));
    }

    public function update(Request $request, Grupo $grupo, Evaluacion $evaluacion)
    {
        if ($evaluacion->grupo_id !== $grupo->id || $grupo->curso->etapa->centro_id !== auth()->user()->centro_id) {
            abort(403);
        }

        $data = $request->validate([
            'nota' => 'sometimes|numeric|min:0|max:10',
            'fecha' => 'sometimes|date',
            'observaciones' => 'sometimes|string|max:1000',
        ]);

        $evaluacion->update($data);
        return response()->json($evaluacion->fresh()->load(['alumno', 'asignatura']));
    }

    public function destroy(Grupo $grupo, Evaluacion $evaluacion)
    {
        if ($evaluacion->grupo_id !== $grupo->id || $grupo->curso->etapa->centro_id !== auth()->user()->centro_id) {
            abort(403);
        }

        $evaluacion->delete();
        return response()->json(['message' => 'Evaluación eliminada']);
    }
}
