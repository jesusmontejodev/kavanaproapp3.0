<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudCliente;
use App\Models\Cliente;
use App\Models\Lead;
use App\Models\User;

class AdminLeadToClienteController extends Controller
{
    public function index()
    {
        // Obtener todas las solicitudes pendientes con relaciones
        $solicitudesPendientes = SolicitudCliente::with(['lead', 'user'])
            ->where('estado', 'pendiente')
            ->latest()
            ->get();

        // Obtener estadísticas
        $estadisticas = [
            'pendientes' => $solicitudesPendientes->count(),
            'aprobadas' => SolicitudCliente::where('estado', 'aprobada')->count(),
            'rechazadas' => SolicitudCliente::where('estado', 'rechazada')->count(),
            'total_clientes' => Cliente::count()
        ];

        return view('adminleadtocliente.index', compact('solicitudesPendientes', 'estadisticas'));
    }

    public function aprobarSolicitud(Request $request, $id)
    {
        $solicitud = SolicitudCliente::with('lead')->findOrFail($id);

        // Validar los datos necesarios
        $request->validate([
            'inmueble_comprado' => 'required|string|max:255',
            'precio_compra' => 'required|numeric|min:0',
            'direccion_inmueble' => 'required|string|max:500',
            'tipo_inmueble' => 'required|string',
            'fecha_entrega_estimada' => 'required|date',
        ]);

        // Crear el cliente a partir del lead
        Cliente::create([
            'id_user' => $solicitud->id_user,
            'id_lead' => $solicitud->id_lead,
            'nombre_completo' => $solicitud->lead->nombre . ' ' . $solicitud->lead->apellido,
            'email' => $solicitud->lead->correo,
            'telefono' => $solicitud->lead->telefono,
            'inmueble_comprado' => $request->inmueble_comprado,
            'precio_compra' => $request->precio_compra,
            'direccion_inmueble' => $request->direccion_inmueble,
            'tipo_inmueble' => $request->tipo_inmueble,
            'estado_entrega' => 'contrato_firmado',
            'fecha_compra' => now()->format('Y-m-d'), // Asegurar formato date
            'fecha_entrega_estimada' => $request->fecha_entrega_estimada,
            // Cambiar esto a texto plano o usar datetime si cambiaste la migración
            'ultimo_seguimiento' => 'Cliente creado a partir de lead aprobado. ' . $solicitud->descripcion_solicitud,
            'proximo_seguimiento' => now()->addDays(7)->format('Y-m-d'), // Asegurar formato date
            'observaciones_entrega' => $request->comentario_admin ?? '',
        ]);

        // Actualizar la solicitud
        $solicitud->update([
            'estado' => 'aprobada',
            'comentario_admin' => $request->comentario_admin ?? 'Solicitud aprobada y cliente creado.',
            'fecha_revision' => now(),
        ]);

        return back()->with('success', 'Solicitud aprobada y cliente creado exitosamente.');
    }

    public function rechazarSolicitud(Request $request, $id)
    {
        $solicitud = SolicitudCliente::findOrFail($id);

        $request->validate([
            'comentario_admin' => 'required|string|max:1000',
        ]);

        $solicitud->update([
            'estado' => 'rechazada',
            'comentario_admin' => $request->comentario_admin,
            'fecha_revision' => now(),
        ]);

        return back()->with('success', 'Solicitud rechazada correctamente.');
    }

    public function historial()
    {
        $solicitudes = SolicitudCliente::with(['lead', 'user'])
            ->whereIn('estado', ['aprobada', 'rechazada'])
            ->latest()
            ->paginate(20);

        return view('adminleadtocliente.historial', compact('solicitudes'));
    }
}
