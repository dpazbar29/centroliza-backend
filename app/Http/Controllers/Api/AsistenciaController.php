<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use App\Models\User;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    public function index(Grupo $grupo)
    {
        if ($grupo->curso->etapa->centro_id !== auth()->user()->centro_id) {
            abort(403);
        }

        $asistencias = $grupo->asistencias()->with(['alumno:id,name'])->whereDate('fecha', Carbon::today())->orderBy('alumno_id')->get();

        return response()->json($asistencias);
    }

    public function store(Request $request, Grupo $grupo)
    {
        if ($grupo->curso->etapa->centro_id !== auth()->user()->centro_id) {
            abort(403);
        }

        $data = $request->validate([
            'alumno_id' => 'required|exists:usuarios,id',
            'estado' => 'required|in:presente,ausente,justificada,tardia',
            'hora_entrada' => 'nullable|date_format:H:i',
            'justificacion' => 'nullable|string|max:500',
            'tipo' => 'sometimes|in:normal,examen,actividad',
        ]);

        $matricula = $grupo->matriculas()->where('alumno_id', $data['alumno_id'])->where('estado', 'activa')->firstOrFail();

        $existente = Asistencia::where('grupo_id', $grupo->id)->where('alumno_id', $data['alumno_id'])->whereDate('fecha', Carbon::today())->first();

        if ($existente) {
            return response()->json(['error' => 'Asistencia ya registrada hoy'], 422);
        }

        $asistencia = $grupo->asistencias()->create(array_merge($data, [
            'fecha' => Carbon::today(),
        ]));

        return response()->json($asistencia->load('alumno'), 201);
    }

    public function show(Grupo $grupo, Asistencia $asistencia)
    {
        if ($asistencia->grupo_id !== $grupo->id || $grupo->curso->etapa->centro_id !== auth()->user()->centro_id) {
            abort(404);
        }

        return response()->json($asistencia->load(['alumno', 'grupo.profesorTutor']));
    }

    public function update(Request $request, Grupo $grupo, Asistencia $asistencia)
    {
        if ($asistencia->grupo_id !== $grupo->id || $grupo->curso->etapa->centro_id !== auth()->user()->centro_id) {
            abort(403);
        }

        $data = $request->validate([
            'estado' => 'sometimes|in:presente,ausente,justificada,tardia',
            'hora_entrada' => 'nullable|date_format:H:i',
            'justificacion' => 'nullable|string|max:500',
            'tipo' => 'sometimes|in:normal,examen,actividad',
        ]);

        $asistencia->update($data);
        return response()->json($asistencia->fresh()->load('alumno'));
    }

    public function destroy(Grupo $grupo, Asistencia $asistencia)
    {
        if ($asistencia->grupo_id !== $grupo->id || $grupo->curso->etapa->centro_id !== auth()->user()->centro_id) {
            abort(403);
        }

        $asistencia->delete();
        return response()->json(['message' => 'Asistencia eliminada']);
    }
}
