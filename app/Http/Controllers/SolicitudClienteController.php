<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etapa;
use App\Models\User;
use App\Models\Lead;
use App\Models\SolicitudCliente;
use Illuminate\Support\Facades\Auth;

class SolicitudClienteController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_lead' => 'required|exists:leads,id',
            'descripcion_solicitud' => 'required|string|max:1000',
        ]);

        // Verificar que el lead pertenece al usuario autenticado
        $lead = Lead::where('id', $validated['id_lead'])
                    ->where('id_user', Auth::id())
                    ->firstOrFail();

        // Crear la solicitud con el usuario autenticado
        SolicitudCliente::create([
            'id_user' => Auth::id(),
            'id_lead' => $validated['id_lead'],
            'descripcion_solicitud' => $validated['descripcion_solicitud'],
            'estado' => 'pendiente',
        ]);

        return back()->with('success', 'Solicitud enviada correctamente. Espera la aprobación del administrador.');
    }
}
