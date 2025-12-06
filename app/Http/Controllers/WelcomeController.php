<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Show the application welcome page.
     */
    public function __invoke()
    {
        // Obtener todos los proyectos
        $proyectos = Proyecto::all();

        // O si quieres solo los proyectos activos o con algún filtro:
        // $proyectos = Proyecto::where('estado', 'activo')->get();

        return view('welcome', compact('proyectos'));
    }
}
