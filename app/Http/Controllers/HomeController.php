<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the landing page with projects.
     */
    public function index()
    {
        // Obtener proyectos con sus relaciones para la landing page
        $proyectos = Proyecto::with(['mediaProyectos', 'linkProyectos'])
                            ->latest()
                            ->get();

        return view('home.index', compact('proyectos'));
    }
}
