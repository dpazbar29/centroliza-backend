<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Centro;
use App\Models\Etapa;
use Illuminate\Http\Request;

class EtapaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Centro $centro)
    {
        $etapas = $centro->etapas()->withCount('cursos')->orderBy('orden')->get(['id', 'nombre', 'orden']);
        return response()->json($etapas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Centro $centro)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100|unique:etapas,nombre,NULL,id,centro_id,' . $centro->id,
            'orden' => 'nullable|integer|min:0',
            'anos_duracion' => 'required|integer|min:1|max:6',
        ]);

        $etapa = $centro->etapas()->create($data);
        return response()->json($etapa->load('cursos'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Centro $centro, Etapa $etapa)
    {
        if ($etapa->centro_id !== $centro->id) abort(404);
        return response()->json($etapa->load(['cursos.asignaturas']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Centro $centro, Etapa $etapa)
    {
        if ($etapa->centro_id !== $centro->id) abort(403);

        $data = $request->validate([
            'nombre' => 'sometimes|string|max:100|unique:etapas,nombre,' . $etapa->id . ',id,centro_id,' . $centro->id,
            'orden' => 'sometimes|integer|min:0',
            'anos_duracion' => 'sometimes|integer|min:1|max:6',
        ]);

        $etapa->update($data);
        return response()->json($etapa->fresh()->load('cursos'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Centro $centro, Etapa $etapa)
    {
        if ($etapa->centro_id !== $centro->id) abort(403);
        $etapa->delete();
        return response()->json(['message' => 'Etapa eliminada']);
    }
}
