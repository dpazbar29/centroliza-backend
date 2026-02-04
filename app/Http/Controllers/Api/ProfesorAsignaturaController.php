<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\Centro;
use App\Models\Etapa;
use App\Models\Curso;
use Illuminate\Http\Request;

class ProfesorAsignaturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Centro $centro, Etapa $etapa, Curso $curso, Asignatura $asignatura)
    {
        $profesores = $asignatura->profesores()->select('usuarios.id', 'usuarios.name', 'usuarios.email', 'usuarios.dni')->where('usuarios.role', 'profesor')->get();
        return response()->json($profesores);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
