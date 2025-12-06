<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;

class PaginaUsuarioController extends Controller
{
    public function index()
    {
        // Obtener el ID del usuario autenticado
        $id_usuario = Auth::id();

        // Obtener proyectos (ajusta según tus necesidades)
        // Opción 1: Todos los proyectos
        $proyectos = Proyecto::all();

        // Opción 2: Solo proyectos del usuario actual (si tienes relación)
        // $proyectos = Proyecto::where('user_id', $id_usuario)->get();

        // Opción 3: Proyectos en los que el usuario tiene acceso
        // $proyectos = Auth::user()->proyectos;

        return view('paginasusuario.index', compact('proyectos', 'id_usuario'));
    }
}
