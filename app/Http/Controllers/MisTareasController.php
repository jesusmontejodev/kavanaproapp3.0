<?php

namespace App\Http\Controllers;

use App\Models\GrupoTarea;
use App\Models\UsuarioTarea;
use Illuminate\Support\Facades\Auth;

class MisTareasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();

        // Obtener todos los grupos con sus tareas
        $grupoTareasWithTareas = GrupoTarea::with('tareas')->get();

        // Calcular total de tareas
        $totalTareas = 0;
        foreach ($grupoTareasWithTareas as $grupo) {
            $totalTareas += $grupo->tareas->count();
        }

        // Obtener tareas completadas del usuario para mostrar estado
        $tareasCompletadas = UsuarioTarea::where('id_user', $userId)
            ->where('completado', true)
            ->pluck('id_tarea')
            ->toArray();

        return view('mistareas.index', compact(
            'grupoTareasWithTareas',
            'totalTareas',
            'tareasCompletadas'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( $request, string $id)
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
