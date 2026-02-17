<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Centro;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CentroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!$request->user()->centro_id) {
            return response()->json([], 200);
        }
        
        $centro = Centro::withCount([
            'usuarios as alumnos_count' => fn($q) => $q->where('role', 'alumno'),
            'usuarios as profesores_count' => fn($q) => $q->where('role', 'profesor'),
            'etapas as etapas_count'
        ])
        ->find($request->user()->centro_id);

        if (!$centro) {
            return response()->json([], 200);
        }
        
        return response()->json([$centro]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->user()->role !== 'director') {
            abort(403, 'Solo directores pueden crear centros');
        }

        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'email_director' => 'required|email|unique:centros,email_director',
            'telefono' => 'nullable|string|max:15',
        ]);

        $data['slug'] = Str::slug($data['nombre']);
        $centro = Centro::create($data);

        $request->user()->update(['centro_id' => $centro->id]);

        return response()->json($centro->loadCount([
            'usuarios', 'etapas'
        ]), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Centro $centro, Request $request)
    {
        if ($centro->id != $request->user()->centro_id) {
            abort(403, 'Acceso denegado');
        }
        
        return response()->json($centro->load([
            'usuarios' => fn($q) => $q->select('id', 'name', 'role', 'status')->limit(10),
            'etapas.cursos' => fn($q) => $q->limit(5),
            'rolesJerarquia' => fn($q) => $q->with('usuario:id,name')->limit(10)
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Centro $centro)
    {
        if ($centro->id != $request->user()->centro_id) {
            abort(403);
        }

        $data = $request->validate([
            'nombre' => 'sometimes|string|max:100',
            'email_director' => 'sometimes|email|unique:centros,email_director,' . $centro->id,
            'telefono' => 'sometimes|string|max:15',
        ]);

        $centro->update($data);
        return response()->json($centro->fresh()->loadCount(['usuarios', 'etapas']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Centro $centro, Request $request)
    {
        if ($centro->id != $request->user()->centro_id || $request->user()->role !== 'director') {
            abort(403);
        }

        $centro->delete();
        $request->user()->update(['centro_id' => null]);
        
        return response()->json(['message' => 'Centro eliminado']);
    }
}
