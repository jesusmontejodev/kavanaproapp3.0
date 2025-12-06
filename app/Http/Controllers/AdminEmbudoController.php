<?php

namespace App\Http\Controllers;

use App\Models\Embudo;
use Illuminate\Http\Request;

class AdminEmbudoController extends Controller
{
    public function index()
    {
        //
        $embudos = Embudo::get();

        return view('adminembudos.index', compact('embudos'));
    }

    public function create()
    {
        return view('adminembudos.create');
    }

    public function store(Request $request)
    {
        //
        $valided = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);
        // Crear el embudo
        Embudo::create([
            'nombre' => $valided['nombre'],
            'descripcion' => $valided['descripcion'],
        ]);
        return redirect()->route('adminembudos.index')->with('success', 'Embudo creado exitosamente!');
    }

    public function show($id)
    {
        $embudo = Embudo::with('etapas')->findOrFail($id);
        $tareasembudo = $embudo->etapas;

        // Calcular el nuevo orden para la próxima etapa
        $ultimoOrden = $tareasembudo->max('orden') ?? 0;
        $nuevoOrden = $ultimoOrden + 1;

        return view('adminembudos.show', compact('embudo', 'tareasembudo', 'nuevoOrden'));
    }

    public function edit($id)
    {
        $embudo = Embudo::with('etapas')->findOrFail($id);
        $tareasembudo = $embudo->etapas;

        // Calcular el nuevo orden para la próxima etapa
        $ultimoOrden = $tareasembudo->max('orden') ?? 0;
        $nuevoOrden = $ultimoOrden + 1;
        return view('adminembudos.edit', compact('embudo', 'tareasembudo', 'nuevoOrden'));
    }

    public function update(Request $request, $id)
    {
        // Validar los datos
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        // Buscar el embudo
        $embudo = Embudo::findOrFail($id);

        // Actualizar el embudo
        $embudo->update([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
        ]);

        // Redireccionar según de dónde vino la solicitud
        if ($request->has('from_edit_page')) {
            return redirect()->route('adminembudos.edit', $embudo->id)
                ->with('success', 'Embudo actualizado exitosamente!');
        }

        return redirect()->route('adminembudos.index')
            ->with('success', 'Embudo actualizado exitosamente!');
    }

    public function destroy($id)
    {
        try {
            // Buscar el registro primero
            $embudo = Embudo::find($id);

            // Verificar si existe
            if (!$embudo) {
                return response()->json([
                    'message' => 'Registro no encontrado'
                ], 404);
            }

            // Eliminar el registro
            $embudo->delete();

            return response()->json([
                'message' => 'Registro eliminado correctamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el registro',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
