<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LinkProyecto;

class LinkProyectoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $links = LinkProyecto::with('proyecto')->latest()->paginate(10);
        return view('linkproyecto.index', compact('links'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Si necesitas pasar proyectos para un select
        $proyectos = \App\Models\Proyecto::all();
        return view('linkproyecto.create', compact('proyectos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_proyecto' => 'required|exists:proyectos,id',
            'url_archivo' => 'required|url|max:500', // Cambié a required y url
            'descripcion' => 'nullable|string|max:500'
        ]);

        $linkProyecto = LinkProyecto::create([
            'id_proyecto' => $validated['id_proyecto'],
            'url_archivo' => $validated['url_archivo'], // Corregí este campo (era 'url_imagen')
            'descripcion' => $validated['descripcion'] ?? null
        ]);

        return redirect()->back()->with('success', 'Enlace creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $link = LinkProyecto::with('proyecto')->findOrFail($id);
        return view('linkproyecto.show', compact('link'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $link = LinkProyecto::findOrFail($id);
        $proyectos = \App\Models\Proyecto::all();
        return view('linkproyecto.edit', compact('link', 'proyectos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $link = LinkProyecto::findOrFail($id);

        $validated = $request->validate([
            'id_proyecto' => 'required|exists:proyectos,id',
            'url_archivo' => 'required|url|max:500',
            'descripcion' => 'nullable|string|max:500'
        ]);

        $link->update([
            'id_proyecto' => $validated['id_proyecto'],
            'url_archivo' => $validated['url_archivo'],
            'descripcion' => $validated['descripcion'] ?? null
        ]);

        return redirect()->route('linkproyecto.index')
            ->with('success', 'Enlace actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $link = LinkProyecto::findOrFail($id);
        $link->delete();

        return redirect()->back()->with('success', 'Enlace eliminado correctamente.');
    }
}
