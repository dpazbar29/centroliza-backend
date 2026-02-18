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
    public function index($grupo)
    {
        $grupo = Grupo::findOrFail($grupo);

        $asistencias = $grupo->asistencias()
            ->with(['alumno:id,name'])
            ->whereDate('fecha', today())
            ->orderBy('alumno_id')
            ->get();

        $alumnosGrupo = $grupo->matriculas()
            ->where('estado', 'activa')
            ->pluck('alumno_id')
            ->toArray();

        return response()->json([
            'asistencias' => $asistencias,
            'alumnos_grupo' => $alumnosGrupo
        ]);
    }

    public function store(Request $request, $grupoId)
    {
        $grupo = Grupo::findOrFail($grupoId);

        if ($grupo->curso->etapa->centro_id !== auth()->user()->centro_id) {
            abort(403);
        }

        $data = $request->validate([
            'alumno_id' => 'required|exists:usuarios,id',
            'estado' => 'required|in:presente,ausente,justificada,retraso',
            'hora_entrada' => 'nullable|date_format:H:i',
            'justificacion' => 'nullable|string|max:500',
            'tipo' => 'sometimes|in:normal,examen,actividad',
        ]);

        $matricula = $grupo->matriculas()
            ->where('alumno_id', $data['alumno_id'])
            ->where('estado', 'activa')
            ->firstOrFail();

        $existente = Asistencia::where('grupo_id', $grupo->id)
            ->where('alumno_id', $data['alumno_id'])
            ->whereDate('fecha', Carbon::parse($data['fecha'] ?? Carbon::today()))
            ->first();

        if ($existente) {
            $existente->update($data);
            $asistencia = $existente->fresh()->load('alumno');
        } else {
            $asistencia = $grupo->asistencias()->create(array_merge($data, [
                'fecha' => Carbon::parse($data['fecha']),
                'hora_entrada' => now()->format('H:i:s'),
            ]));
        }

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
