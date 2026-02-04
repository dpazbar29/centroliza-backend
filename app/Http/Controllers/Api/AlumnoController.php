<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Centro;
use App\Models\Etapa;
use App\Models\Curso;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // TODOS los alumnos del centro
    public function index(Centro $centro)
    {
        $alumnos = User::where('centro_id', $centro->id)->where('role', 'alumno')->get(['id', 'name', 'email', 'dni']);
        return response()->json($alumnos);
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
