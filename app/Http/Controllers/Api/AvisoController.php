<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Centro;
use App\Models\Aviso;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AvisoController extends Controller
{
    public function index(Centro $centro)
    {
        if ($centro->id !== auth()->user()->centro_id) {
            abort(403);
        }

        $avisos = $centro->avisos()->activos()->orderBy('fecha_publicacion', 'desc')->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($avisos);
    }

    public function store(Request $request, Centro $centro)
    {
        if ($centro->id !== auth()->user()->centro_id) {
            abort(403);
        }

        $data = $request->validate([
            'titulo' => 'required|string|max:100',
            'contenido' => 'required|string|max:2000',
            'tipo' => 'required|in:general,profesores,alumnos,familias,urgente',
            'fecha_publicacion' => 'sometimes|date|after_or_equal:today',
            'fecha_expiracion' => 'nullable|date|after:fecha_publicacion',
        ]);

        $data['centro_id'] = $centro->id;
        $data['fecha_publicacion'] ??= now()->format('Y-m-d');
        $data['visible'] = true;

        $aviso = Aviso::create($data);
        return response()->json($aviso, 201);
    }

    public function show(Centro $centro, Aviso $aviso)
    {
        if ($aviso->centro_id !== $centro->id || $centro->id !== auth()->user()->centro_id) {
            abort(404);
        }

        return response()->json($aviso);
    }

    public function update(Request $request, Centro $centro, Aviso $aviso)
    {
        if ($aviso->centro_id !== $centro->id || $centro->id !== auth()->user()->centro_id) {
            abort(403);
        }

        $data = $request->validate([
            'titulo' => 'sometimes|string|max:100',
            'contenido' => 'sometimes|string|max:2000',
            'tipo' => 'sometimes|in:general,profesores,alumnos,familias,urgente',
            'fecha_publicacion' => 'sometimes|date',
            'fecha_expiracion' => 'nullable|date|after:fecha_publicacion',
            'visible' => 'sometimes|boolean',
        ]);

        $aviso->update($data);
        return response()->json($aviso->fresh());
    }

    public function destroy(Centro $centro, Aviso $aviso)
    {
        if ($aviso->centro_id !== $centro->id || $centro->id !== auth()->user()->centro_id) {
            abort(403);
        }

        $aviso->delete();
        return response()->json(['message' => 'Aviso eliminado']);
    }
}
