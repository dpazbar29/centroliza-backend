<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RolJerarquia;
use Illuminate\Http\Request;

class RolJerarquiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Centro $centro)
    {
        $jerarquia = $centro->rolesJerarquia()
            ->ordenados()
            ->with('user:id,name,email,role')
            ->get([
                'id', 'user_id', 'tipo', 'orden_prioridad', 'fecha_asignacion'
            ]);
            
        return response()->json($jerarquia);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Centro $centro)
    {
        if ($centro->id !== $request->user()->centro_id) {
            abort(403);
        }

        $data = $request->validate([
            'user_id' => 'required|exists:usuarios,id|unique:roles_jerarquia,user_id,NULL,id,centro_id,' . $centro->id,
            'tipo' => 'required|in:directivo,jefe_estudios,coordinador,tutor',
            'orden_prioridad' => 'nullable|integer|min:0|max:100',
            'fecha_asignacion' => 'nullable|date|after_or_equal:today',
        ]);

        $data['centro_id'] = $centro->id;
        $data['fecha_asignacion'] ??= now()->format('Y-m-d');

        $rol = RolJerarquia::create($data);
        return response()->json($rol->load('user'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Centro $centro, RolJerarquia $rol)
    {
        if ($rol->centro_id !== $centro->id) abort(404);
        return response()->json($rol->load('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Centro $centro, RolJerarquia $rol)
    {
        if ($rol->centro_id !== $centro->id) abort(403);

        $data = $request->validate([
            'tipo' => 'sometimes|in:directivo,jefe_estudios,coordinador,tutor',
            'orden_prioridad' => 'sometimes|integer|min:0|max:100',
            'fecha_asignacion' => 'sometimes|date',
        ]);

        $rol->update($data);
        return response()->json($rol->fresh()->load('user'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Centro $centro, RolJerarquia $rol)
    {
        if ($rol->centro_id !== $centro->id) abort(403);
        $rol->delete();
        return response()->json(['message' => 'Rol eliminado']);
    }
}
