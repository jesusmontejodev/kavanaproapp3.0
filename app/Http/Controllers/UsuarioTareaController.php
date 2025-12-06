<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsuarioTarea;

class UsuarioTareaController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_tarea' => 'required|exists:tareas,id',
            'id_user' => 'required|exists:users,id',
            'completado' => 'required|boolean'
        ]);

        // Verificar si ya existe el registro
        $registroExistente = UsuarioTarea::where('id_tarea', $data['id_tarea'])
            ->where('id_user', $data['id_user'])
            ->first();

        if ($registroExistente) {
            // Actualizar registro existente
            $registroExistente->update([
                'completado' => $data['completado'],
                'fecha_completado' => $data['completado'] ? now() : null
            ]);

            return redirect()->back()->with('success', 'Tarea actualizada correctamente.');
        } else {
            // Crear nuevo registro
            $newData = [
                'id_tarea' => $data['id_tarea'],
                'id_user' => $data['id_user'],
                'completado' => $data['completado'],
                'fecha_completado' => $data['completado'] ? now() : null
            ];

            UsuarioTarea::create($newData);
            return redirect()->back()->with('success', 'Tarea asignada correctamente.');
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'completado' => 'required|boolean'
        ]);

        $usuarioTarea = UsuarioTarea::findOrFail($id);

        $usuarioTarea->update([
            'completado' => $data['completado'],
            'fecha_completado' => $data['completado'] ? now() : null
        ]);

        return redirect()->back()->with('success', 'Estado de la tarea actualizado correctamente.');
    }

    // Este método debería estar en el Livewire component, no en el controller
    // O si está en el controller, debe ser público para usarse en la vista
    public function existeTareaUsuario($id_user, $id_tarea)
    {
        return UsuarioTarea::where('id_tarea', $id_tarea)
                    ->where('id_user', $id_user)
                    ->first();
    }
}
