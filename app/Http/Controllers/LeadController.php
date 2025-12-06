<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Embudo;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LeadController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Usuario no autenticado');
        }

        // Embudos con el contador de leads usando la nueva relación
        $embudos = Embudo::withCount('leads')->get();

        // Leads del usuario con todas las relaciones necesarias
        $leads = Lead::with(['etapa', 'embudo'])
            ->where('id_user', $user->id)
            ->orderBy('fecha_creado', 'desc')
            ->get();

        // Eliminar tokens anteriores (opcional - considera si realmente necesitas esto)
        // $user->tokens()->delete();

        // Crear nuevo token para la sesión actual
        $token = $user->createToken('FrontendToken')->plainTextToken;

        return view('lead.index', compact('token', 'leads', 'embudos'));
    }

    // Método para crear un nuevo lead (si no lo tienes)
    public function create()
    {
        $embudos = Embudo::with('etapas')->get();
        return view('lead.create', compact('embudos'));
    }

    // Método para guardar un nuevo lead (si no lo tienes)
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email',
            'numero_telefono' => 'nullable|string',
            'id_etapa' => 'required|exists:etapas,id',
            'nombre_proyecto' => 'nullable|string|max:255',
        ]);

        Lead::create([
            'id_user' => Auth::id(),
            'id_etapa' => $request->id_etapa,
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'numero_telefono' => $request->numero_telefono,
            'fecha_creado' => now(),
            'nombre_proyecto' => $request->nombre_proyecto,
            'orden' => 0, // Puedes ajustar esto según tu lógica
        ]);

        return redirect()->route('leads.index')
            ->with('success', 'Lead creado exitosamente');
    }

    // Método para mostrar un lead específico
    public function show(Lead $lead)
    {
        // Verificar que el lead pertenezca al usuario
        if ($lead->id_user != Auth::id()) {
            abort(403, 'No autorizado');
        }

        $lead->load(['etapa', 'embudo', 'solicitudes']);

        return view('lead.show', compact('lead'));
    }

    // Método para editar un lead
    public function edit(Lead $lead)
    {
        // Verificar que el lead pertenezca al usuario
        if ($lead->id_user != Auth::id()) {
            abort(403, 'No autorizado');
        }

        $embudos = Embudo::with('etapas')->get();

        return view('lead.edit', compact('lead', 'embudos'));
    }

    // Método para actualizar un lead
    public function update(Request $request, Lead $lead)
    {
        // Verificar que el lead pertenezca al usuario
        if ($lead->id_user != Auth::id()) {
            abort(403, 'No autorizado');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email',
            'numero_telefono' => 'nullable|string',
            'id_etapa' => 'required|exists:etapas,id',
            'nombre_proyecto' => 'nullable|string|max:255',
        ]);

        $lead->update([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'numero_telefono' => $request->numero_telefono,
            'id_etapa' => $request->id_etapa,
            'nombre_proyecto' => $request->nombre_proyecto,
        ]);

        return redirect()->route('leads.index')
            ->with('success', 'Lead actualizado exitosamente');
    }

    // Método para eliminar un lead
    public function destroy(Lead $lead)
    {
        // Verificar que el lead pertenezca al usuario
        if ($lead->id_user != Auth::id()) {
            abort(403, 'No autorizado');
        }

        $lead->delete();

        return redirect()->route('leads.index')
            ->with('success', 'Lead eliminado exitosamente');
    }
}
