<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GrupoTarea;
use App\Models\Tarea;

class AdminUsuariosController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el término de búsqueda
        $search = $request->get('search');

        // Consulta base de usuarios
        $usersQuery = User::query();

        // Aplicar filtro de búsqueda si existe
        if ($search) {
            $usersQuery->where('email', 'LIKE', "%{$search}%")
                        ->orWhere('name', 'LIKE', "%{$search}%");
            }

        // Obtener usuarios
        $allUsers = $usersQuery->get();

        // Obtener grupos con tareas
        $GrupoTareasWithTareas = GrupoTarea::with('tareas')->get();

        return view('adminusuarios.index', compact('allUsers', 'GrupoTareasWithTareas', 'search'));
    }

    private function ordenarTareas()
    {
        // Tu lógica de ordenación aquí
    }
}
