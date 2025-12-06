<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etapa;

class AdminEtapaController extends Controller
{

    public function index()
    {
        //
        echo "Index Etapas";
    }

    public function edit($id){

    }




public function store(Request $request)
{
    $validated = $request->validate([
        'id_embudo' => 'required|exists:embudos,id',
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
    ]);

    // Calcular el nuevo orden automáticamente
    $ultimoOrden = Etapa::where('id_embudo', $validated['id_embudo'])
                        ->max('orden') ?? 0;

    $nuevoOrden = $ultimoOrden + 1;

    Etapa::create([
        'id_embudo' => $validated['id_embudo'],
        'nombre' => $validated['nombre'],
        'descripcion' => $validated['descripcion'],
        'orden' => $nuevoOrden, // Usar el orden calculado
    ]);

    return redirect()->back()->with('success', 'Etapa creada exitosamente!');
}

    public function destroy($id)
    {
        Etapa::destroy($id);
        return redirect()->back()->with('success', 'Embudo eliminado exitosamente!');

    }


}
