<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\GrupoTarea;

class TareaController extends Controller
{
    public function index()
    {
        // Listar todas las tareas (opcional, si necesitas una vista general)
        $tareas = Tarea::with('grupoTarea')->latest()->get();
        return view('tareas.index', compact('tareas'));
    }

    public function create()
    {
        // Mostrar formulario para crear nueva tarea
        $grupoTareas = GrupoTarea::all();
        return view('tareas.create', compact('grupoTareas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_grupo_tarea' => 'required|exists:grupo_tareas,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'orden' => 'nullable|integer'
        ]);

        Tarea::create([
            'id_grupo_tarea' => $validated['id_grupo_tarea'],
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? '',
            'orden' => $validated['orden'] ?? 0
        ]);

        return redirect()->route('grupotareas.show', $validated['id_grupo_tarea'])
                        ->with('success', 'Tarea creada exitosamente!');
    }

    public function show($id)
    {
        // Mostrar detalles de una tarea específica
        $tarea = Tarea::with('grupoTarea')->findOrFail($id);
        return view('tareas.show', compact('tarea'));
    }

    public function edit($id)
    {
        // Mostrar formulario para editar tarea
        $tarea = Tarea::findOrFail($id);
        $grupoTareas = GrupoTarea::all();

        return view('tareas.edit', compact('tarea', 'grupoTareas'));
    }

    public function update(Request $request, $id)
    {
        // Validar datos
        $validated = $request->validate([
            'id_grupo_tarea' => 'required|exists:grupo_tareas,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'orden' => 'nullable|integer'
        ]);

        // Buscar y actualizar la tarea
        $tarea = Tarea::findOrFail($id);
        $tarea->update([
            'id_grupo_tarea' => $validated['id_grupo_tarea'],
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? '',
            'orden' => $validated['orden'] ?? 0
        ]);

        // Redirigir al grupo de tareas
        return redirect()->route('grupotareas.show', $validated['id_grupo_tarea'])
                        ->with('success', 'Tarea actualizada exitosamente!');
    }

    public function destroy($id)
    {
        // Eliminar una tarea
        $tarea = Tarea::findOrFail($id);
        $idGrupoTarea = $tarea->id_grupo_tarea;

        $tarea->delete();

        return redirect()->route('grupotareas.show', $idGrupoTarea)
                        ->with('success', 'Tarea eliminada exitosamente!');
    }

    // Método adicional para cambiar el estado de la tarea (si tienes columna 'estado')
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,en_progreso,completada'
        ]);

        $tarea = Tarea::findOrFail($id);
        $tarea->update(['estado' => $request->estado]);

        return response()->json([
            'success' => true,
            'message' => 'Estado de tarea actualizado'
        ]);
    }

    // Método para reordenar tareas
    public function reorder(Request $request)
    {
        $request->validate([
            'tareas' => 'required|array'
        ]);

        foreach ($request->tareas as $index => $tareaId) {
            Tarea::where('id', $tareaId)->update(['orden' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
