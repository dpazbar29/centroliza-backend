<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Centro;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // TODOS los alumnos del centro
    public function index(Centro $centro)
    {
        $alumnos = $centro->usuarios()
            ->where('role', 'alumno')
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'dni', 'telefono', 'fecha_nacimiento']);
            
        return response()->json($alumnos);
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
            'fecha_nacimiento' => 'nullable|date|before:today',
            'telefono' => 'nullable|string|max:15',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $data['centro_id'] = $centro->id;
        $data['role'] = 'alumno';
        // $data['status'] = 'pending';
        $data['status'] = auth()->user()->role === 'director' ? 'active' : 'pending';
        $data['password'] = bcrypt($data['password']);

        $alumno = User::create($data);
        return response()->json($alumno, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Centro $centro, User $alumno)
    {
        if ($alumno->centro_id !== $centro->id || $alumno->role !== 'alumno') {
            abort(404);
        }
        
        $alumno->load(['matriculas.curso.etapa', 'matriculas.tutor']);
        return response()->json($alumno);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Centro $centro, User $alumno)
    {
        if ($alumno->centro_id !== $centro->id || $alumno->role !== 'alumno') {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|unique:usuarios,email,' . $alumno->id,
            'dni' => 'sometimes|string|unique:usuarios,dni,' . $alumno->id . '|max:9',
            'telefono' => 'sometimes|string|max:15',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'status' => 'sometimes|in:pending,active',
        ]);

        $alumno->update($data);
        return response()->json($alumno->fresh());
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Centro $centro, User $alumno)
    {
        if ($alumno->centro_id !== $centro->id || $alumno->role !== 'alumno') {
            abort(403);
        }
        
        $alumno->delete();
        return response()->json(['message' => 'Alumno eliminado']);
    }
}