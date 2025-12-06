<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Lead;
use App\Models\User;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Http;

class PublicLeadController extends Controller
{
    /**
     * Guardar lead desde página pública
     * POST /api/leadsusuarios
     */
    public function store(Request $request)
    {
        // Validar datos del formulario
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'numero_telefono' => 'required|string|max:20',
            'id_usuario' => 'required|exists:users,id',
            'id_proyecto' => 'nullable|exists:proyectos,id',
            'nombre_proyecto' => 'nullable|string|max:255',
            'recaptcha_token' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Por favor, completa todos los campos requeridos.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verificar ReCaptcha
        $recaptchaValid = $this->verifyRecaptcha($request->recaptcha_token);

        if (!$recaptchaValid) {
            return response()->json([
                'success' => false,
                'message' => 'La verificación ReCaptcha falló. Por favor, intenta nuevamente.'
            ], 422);
        }

        try {
            // Verificar que el usuario existe
            $usuario = User::find($request->id_usuario);
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado.'
                ], 404);
            }

            // Obtener el siguiente número de orden para este usuario
            $ultimoLead = Lead::where('id_user', $request->id_usuario)
                            ->orderBy('orden', 'desc')
                            ->first();

            $nuevoOrden = $ultimoLead ? $ultimoLead->orden + 1 : 1;

            // Crear el lead
            $lead = Lead::create([
                'id_user' => $request->id_usuario,
                'id_etapa' => null, // Nullable como solicitaste
                'orden' => $nuevoOrden,
                'nombre' => $request->nombre,
                'correo' => $request->correo,
                'numero_telefono' => $request->numero_telefono,
                'fecha_creado' => now(),
                'nombre_proyecto' => $request->nombre_proyecto,
            ]);

            // Aquí podrías agregar:
            // 1. Enviar email de notificación al usuario
            // 2. Enviar email de confirmación al lead
            // 3. Integrar con CRM si es necesario

            return response()->json([
                'success' => true,
                'message' => '¡Gracias por tu interés! Te contactaremos pronto.',
                'lead_id' => $lead->id,
                'data' => [
                    'nombre' => $lead->nombre,
                    'correo' => $lead->correo,
                    'telefono' => $lead->numero_telefono,
                    'proyecto' => $lead->nombre_proyecto,
                    'fecha' => $lead->fecha_creado->format('d/m/Y H:i'),
                    'usuario_destino' => $usuario->name
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al crear lead público: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al procesar tu solicitud. Por favor, intenta nuevamente.'
            ], 500);
        }
    }

    /**
     * Verificar token de ReCaptcha
     */
    private function verifyRecaptcha($token)
    {
        try {
            $secret = env('RECAPTCHA_SECRET', '6Le_');

            if (empty($secret)) {
                \Log::warning('RECAPTCHA_SECRET no configurado en .env');
                return true; // Para desarrollo, puedes retornar true
            }

            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $token,
                'remoteip' => request()->ip()
            ]);

            $data = $response->json();

            return $data['success'] ?? false;

        } catch (\Exception $e) {
            \Log::error('Error verificando ReCaptcha: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener estadísticas de leads para un usuario (opcional)
     * GET /api/leadsusuarios/estadisticas/{userId}
     */
    public function estadisticas($userId)
    {
        try {
            $usuario = User::find($userId);
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado.'
                ], 404);
            }

            $totalLeads = Lead::where('id_user', $userId)->count();
            $leadsHoy = Lead::where('id_user', $userId)
                                ->whereDate('fecha_creado', today())
                                ->count();

            $leadsUltimaSemana = Lead::where('id_user', $userId)
                                ->where('fecha_creado', '>=', now()->subDays(7))
                                ->count();

            return response()->json([
                'success' => true,
                'estadisticas' => [
                    'total_leads' => $totalLeads,
                    'leads_hoy' => $leadsHoy,
                    'leads_ultima_semana' => $leadsUltimaSemana,
                    'usuario' => [
                        'id' => $usuario->id,
                        'nombre' => $usuario->name,
                        'empresa' => $usuario->empresa
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error obteniendo estadísticas: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas.'
            ], 500);
        }
    }
}
