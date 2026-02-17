<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Centro;
use Illuminate\Http\Request;

class ProfesorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Centro $centro)
    {
        $profesores = User::where('centro_id', $centro->id)->where('role', 'profesor')->select('id', 'name', 'email', 'dni', 'telefono', 'fecha_nacimiento')->get();
        
    return response()->json($profesores);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Centro $centro)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email',
            'dni' => 'nullable|string|unique:usuarios,dni|max:9',
            'telefono' => 'nullable|string|max:15',
            'fecha_nacimiento' => 'nullable|date',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $data['centro_id'] = $centro->id;
        $data['role'] = 'profesor';
        $data['status'] = 'active';
        $data['password'] = bcrypt($data['password']);

        $profesor = User::create($data);
        return response()->json($profesor, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Centro $centro, User $profesor)
    {
        if ($profesor->centro_id !== $centro->id || $profesor->role !== 'profesor') {
            abort(404);
        }
        
        $profesor->load(['asignaturas.curso.etapa']);
        return response()->json($profesor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Centro $centro, User $profesor)
    {
        if ($profesor->centro_id !== $centro->id || $profesor->role !== 'profesor') {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|unique:usuarios,email,' . $profesor->id,
            'dni' => 'sometimes|string|unique:usuarios,dni,' . $profesor->id . '|max:9',
            'telefono' => 'sometimes|string|max:15',
            'fecha_nacimiento' => 'sometimes|date|nullable',
        ]);

        $profesor->update($data);
        return response()->json($profesor->fresh()->load('asignaturas'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Centro $centro, User $profesor)
    {
        if ($profesor->centro_id !== $centro->id || $profesor->role !== 'profesor') {
            abort(403);
        }
        
        $profesor->delete();
        return response()->json(['message' => 'Profesor eliminado']);
    }
}
