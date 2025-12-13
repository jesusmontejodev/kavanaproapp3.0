<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\SolicitudCliente;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;

class MisClientesController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $leads = Lead::where('id_user', $userId)->get();
        $misSolicitudes = SolicitudCliente::with(['lead'])
            ->where('id_user', $userId)
            ->latest()
            ->get();

        // Obtener los clientes del usuario actual
        $misClientes = Cliente::where('id_user', $userId)
            ->latest()
            ->get();

        return view('misclientes.index', compact('leads', 'misSolicitudes', 'misClientes'));
    }


    public function show($id)
    {
        $userId = Auth::id();

        // Cargar cliente con sus archivos paginados
        $cliente = Cliente::with(['archivos' => function($query) {
            $query->latest(); // Ordenar por fecha descendente
        }])->where('id_user', $userId)
        ->where('id', $id)
        ->firstOrFail();

        return view('misclientes.show', compact('cliente'));
    }

    public function create()
    {
        return view('misclientes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'inmueble_comprado' => 'required|string|max:255',
            'precio_compra' => 'required|numeric|min:0',
            'direccion_inmueble' => 'required|string|max:500',
            'tipo_inmueble' => 'required|string',
            'estado_entrega' => 'required|in:contrato_firmado,proceso_escrituras,avance_obra,ultimos_detalles,entrega_finalizada',
            'fecha_compra' => 'required|date',
            'fecha_entrega_estimada' => 'required|date',
            'fecha_entrega_real' => 'nullable|date',
            'proximo_seguimiento' => 'nullable|date',
            'observaciones_entrega' => 'nullable|string',
            'notas' => 'nullable|string',
            'id_lead' => 'nullable|exists:leads,id'
        ]);

        $validated['id_user'] = Auth::id();
        $validated['ultimo_seguimiento'] = now();

        Cliente::create($validated);

        return redirect()->route('misclientes.index')
            ->with('success', 'Cliente creado exitosamente');
    }

    public function edit($id)
    {
        $userId = Auth::id();
        $cliente = Cliente::where('id_user', $userId)
            ->where('id', $id)
            ->firstOrFail();

        return view('misclientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $cliente = Cliente::where('id_user', $userId)
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'inmueble_comprado' => 'required|string|max:255',
            'precio_compra' => 'required|numeric|min:0',
            'direccion_inmueble' => 'required|string|max:500',
            'tipo_inmueble' => 'required|string',
            'estado_entrega' => 'required|in:contrato_firmado,proceso_escrituras,avance_obra,ultimos_detalles,entrega_finalizada',
            'fecha_compra' => 'required|date',
            'fecha_entrega_estimada' => 'required|date',
            'fecha_entrega_real' => 'nullable|date',
            'proximo_seguimiento' => 'nullable|date',
            'observaciones_entrega' => 'nullable|string',
            'notas' => 'nullable|string',
            'ultimo_seguimiento' => 'nullable|date'
        ]);

        // Si se actualiza el seguimiento, poner la fecha actual
        if ($request->has('observaciones_entrega') && $request->observaciones_entrega) {
            $validated['ultimo_seguimiento'] = now();
        }

        $cliente->update($validated);

        return redirect()->route('misclientes.show', $cliente->id)
            ->with('success', 'Cliente actualizado exitosamente');
    }

    public function destroy($id)
    {
        $userId = Auth::id();
        $cliente = Cliente::where('id_user', $userId)
            ->where('id', $id)
            ->firstOrFail();

        $cliente->delete();

        return redirect()->route('misclientes.index')
            ->with('success', 'Cliente eliminado exitosamente');
    }

    // Método adicional para registrar seguimiento
    public function registrarSeguimiento(Request $request, $id)
    {
        $userId = Auth::id();
        $cliente = Cliente::where('id_user', $userId)
            ->where('id', $id)
            ->firstOrFail();

        $request->validate([
            'observaciones' => 'required|string',
            'proximo_seguimiento' => 'nullable|date'
        ]);

        $cliente->update([
            'ultimo_seguimiento' => now(),
            'observaciones_entrega' => $request->observaciones,
            'proximo_seguimiento' => $request->proximo_seguimiento
        ]);

        return redirect()->route('misclientes.show', $cliente->id)
            ->with('success', 'Seguimiento registrado exitosamente');
    }

    // Método para actualizar estado de entrega
    public function actualizarEstado(Request $request, $id)
    {
        $userId = Auth::id();
        $cliente = Cliente::where('id_user', $userId)
            ->where('id', $id)
            ->firstOrFail();

        $request->validate([
            'estado_entrega' => 'required|in:contrato_firmado,proceso_escrituras,avance_obra,ultimos_detalles,entrega_finalizada'
        ]);

        $cliente->update([
            'estado_entrega' => $request->estado_entrega,
            'ultimo_seguimiento' => now()
        ]);

        // Si es entrega finalizada, poner fecha real si no existe
        if ($request->estado_entrega == 'entrega_finalizada' && !$cliente->fecha_entrega_real) {
            $cliente->update(['fecha_entrega_real' => now()]);
        }

        return redirect()->route('misclientes.show', $cliente->id)
            ->with('success', 'Estado de entrega actualizado');
    }
}
