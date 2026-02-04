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
    public function index(Centro $centro, Request $request)
    {
        $etapas = Etapa::where('centro_id', $centro->id)->get(['id', 'nombre']);
        return response()->json($etapas);
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
    public function show(Etapa $etapa, Request $request)
    {
        if ($etapa->centro_id != $request->user()->centro_id) {
            abort(403);
        }
        return response()->json($etapa->load('cursos'));
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
