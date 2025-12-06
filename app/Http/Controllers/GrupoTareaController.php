<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GrupoTarea;

class GrupoTareaController extends Controller
{
    public function index()
    {
        $grupoTareas = GrupoTarea::get();
        return view('grupotarea.index', compact('grupoTareas'));
    }

    public function show(GrupoTarea $grupotarea)
    {
        $grupotarea->load('tareas');
        return view('grupotarea.show', compact('grupotarea'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        GrupoTarea::create([
            'nombre' => $validated['nombre']
        ]);

        return redirect()->route('grupotareas.index')
                         ->with('success', 'Grupo de tareas creado exitosamente!');
    }

    // -------------------------
    // 🔵 UPDATE (PUT / PATCH)
    // -------------------------
    public function update(Request $request, GrupoTarea $grupotarea)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        $grupotarea->update([
            'nombre' => $validated['nombre']
        ]);

        return redirect()->route('grupotareas.index')
                         ->with('success', 'Grupo de tareas actualizado correctamente!');
    }

    // -------------------------
    // 🔴 DELETE (DELETE)
    // -------------------------
    public function destroy(GrupoTarea $grupotarea)
    {
        $grupotarea->delete();

        return redirect()->route('grupotareas.index')
                         ->with('success', 'Grupo de tareas eliminado correctamente!');
    }
}
