<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\Centro;
use App\Models\Etapa;
use App\Models\Curso;
use Illuminate\Http\Request;

class AsignaturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Centro $centro, Etapa $etapa, Curso $curso)
    {
        $asignaturas = Asignatura::where('curso_id', $curso->id)->get(['id', 'nombre']);
        return response()->json($asignaturas);
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
    public function show(Centro $centro, Etapa $etapa, Curso $curso, Asignatura $asignatura)
    {
        if ($asignatura->curso_id != $curso->id) abort(404);
        return response()->json($asignatura);
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
