<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Centro;
use Illuminate\Http\Request;

class CentroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // TODOS roles: solo SU centro
        $centro = Centro::findOrFail($request->user()->centro_id);
        return response()->json([$centro]);
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
    public function show(Centro $centro, Request $request)
    {
        // Solo acceso a SU centro
        if ($centro->id != $request->user()->centro_id) {
            abort(403, 'Acceso denegado');
        }
        
        return response()->json($centro->load(['usuarios' => fn($q) => 
            $q->select('id', 'name', 'role')]));
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
