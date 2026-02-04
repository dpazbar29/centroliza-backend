<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Centro;
use App\Models\Etapa;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Centro $centro, Etapa $etapa)
    {
        $cursos = Curso::where('etapa_id', $etapa->id)->get(['id', 'nombre']);
        return response()->json($cursos);
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
    public function show(Centro $centro, Etapa $etapa, Curso $curso)
    {
        if ($curso->etapa_id != $etapa->id) abort(404);
        return response()->json($curso);
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
