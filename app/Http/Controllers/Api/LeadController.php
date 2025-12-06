<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Embudo;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // ← AGREGA ESTE IMPORT


class LeadController extends Controller
{
    // Listar leads del usuario autenticado
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $leads = $user->leads;
        return response()->json($leads);
    }

    // Crear un nuevo lead - ESTE ES EL MÉTODO store() QUE FALTA
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'id_etapa' => 'required|exists:etapas,id',
            'correo' => 'required|email',
            'numero_telefono' => 'required|string',
            'fecha_creado' => 'required|date',
        ]);

        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        DB::transaction(function () use ($user, $request, &$lead, &$debugInfo) {

            // Obtener leads existentes ordenados
            $leadsExistentes = Lead::where('id_user', $user->id)
                                ->where('id_etapa', $request->id_etapa)
                                ->orderBy('orden', 'asc')
                                ->get();

            $debugInfo = [
                'leads_antes' => $leadsExistentes->pluck('orden', 'id'),
                'total_leads' => $leadsExistentes->count()
            ];

            // Mover todos los leads existentes una posición hacia abajo
            foreach ($leadsExistentes as $leadExistente) {
                $leadExistente->orden += 1;
                $leadExistente->save();
            }

            // Crear nuevo lead en posición 1
            $lead = Lead::create([
                'id_user' => $user->id,
                'nombre' => $request->nombre,
                'id_etapa' => $request->id_etapa,
                'orden' => 1,
                'correo' => $request->correo,
                'numero_telefono' => $request->numero_telefono,
                'fecha_creado' => $request->fecha_creado,
            ]);

            // Obtener orden después de la creación
            $debugInfo['leads_despues'] = Lead::where('id_user', $user->id)
                                            ->where('id_etapa', $request->id_etapa)
                                            ->orderBy('orden', 'asc')
                                            ->pluck('orden', 'id');
        });

        return response()->json([
            'message' => 'Lead creado correctamente en la posición superior',
            'lead' => $lead,
            'debug' => $debugInfo
        ], 201);
    }

    // Mostrar un lead específico
    public function show($id)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $lead = Lead::where('id_user', $user->id)->findOrFail($id);

        return response()->json($lead);
    }

    // Actualizar un lead
    public function update(Request $request, $id)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $lead = Lead::where('id_user', $user->id)->findOrFail($id);

        $request->validate([
            'correo' => 'sometimes|email',
            'numero_telefono' => 'sometimes|string',
            'fecha_creado' => 'sometimes|date',
        ]);

        $lead->update($request->only('correo', 'numero_telefono', 'fecha_creado'));

        return response()->json([
            'message' => 'Lead actualizado correctamente',
            'lead' => $lead
        ]);
    }

    // Eliminar un lead
    public function destroy($id)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $lead = Lead::where('id_user', $user->id)->findOrFail($id);

        $lead->delete();

        return response()->json([
            'message' => 'Lead eliminado correctamente'
        ]);
    }

    public function ordenar(Request $request)
    {


    }

    public function getLeadsByEmbudo($id)
    {

        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        // Buscar el embudo
        $embudo = Embudo::with('etapas')->find($id);

        if (!$embudo) {
            return response()->json(['error' => 'Embudo no encontrado'], 404);
        }

        // Obtener los IDs de las etapas de este embudo
        $etapaIds = $embudo->etapas->pluck('id');

        // Obtener leads del usuario para las etapas de este embudo
        $leads = Lead::where('id_user', $user->id)
                    ->whereIn('id_etapa', $etapaIds)
                    ->with('etapa')
                    ->get();

        return response()->json([
            'embudo' => [
                'id' => $embudo->id,
                'nombre' => $embudo->nombre,
                'descripcion' => $embudo->descripcion
            ],
            'etapas' => $embudo->etapas,
            'leads' => $leads
        ]);
    }
    public function leadToNewEtapa(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }

            // Validación final
            $request->validate([
                'etapaOrigen' => 'sometimes|array',
                'etapaOrigen.*.id_lead' => 'sometimes|integer',
                'etapaOrigen.*.nuevo_orden' => 'sometimes|integer|min:1',
                'etapaDestino' => 'sometimes|array',
                'etapaDestino.*.id_lead' => 'sometimes|integer',
                'etapaDestino.*.nuevo_orden' => 'sometimes|integer|min:1',
                'frameLead' => 'required|array',
                'frameLead.id_lead' => 'required|integer',
                'frameLead.id_nueva_etapa' => 'required|integer',
                'frameLead.nueva_posicion' => 'required|integer|min:1'
            ]);

            DB::beginTransaction();

            // 1. Actualizar el LEAD MOVIDO (cambiar etapa y orden)
            Lead::where('id', $request->frameLead['id_lead'])
                ->where('id_user', $user->id)
                ->update([
                    'id_etapa' => $request->frameLead['id_nueva_etapa'],
                    'orden' => $request->frameLead['nueva_posicion']
                ]);

            // 2. Actualizar órdenes en ETAPA ORIGEN
            if (!empty($request->etapaOrigen)) {
                foreach ($request->etapaOrigen as $leadData) {
                    Lead::where('id', $leadData['id_lead'])
                        ->where('id_user', $user->id)
                        ->update(['orden' => $leadData['nuevo_orden']]);
                }
            }

            // 3. Actualizar órdenes en ETAPA DESTINO
            if (!empty($request->etapaDestino)) {
                foreach ($request->etapaDestino as $leadData) {
                    Lead::where('id', $leadData['id_lead'])
                        ->where('id_user', $user->id)
                        ->update(['orden' => $leadData['nuevo_orden']]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Lead movido y órdenes actualizados correctamente',
                'resumen' => [
                    'leads_origen_actualizados' => count($request->etapaOrigen ?? []),
                    'leads_destino_actualizados' => count($request->etapaDestino ?? []),
                    'lead_movido' => $request->frameLead
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            logger()->error('Error en leadToNewEtapa: ' . $e->getMessage());
            return response()->json(['error' => 'Error al actualizar: ' . $e->getMessage()], 500);
        }
    }

    public function actualizarOrdenInterno(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }

            $request->validate([
                'etapaActualizada' => 'required|array',
                'etapaActualizada.*.id_lead' => 'required|integer',
                'etapaActualizada.*.nuevo_orden' => 'required|integer|min:1'
            ]);

            DB::beginTransaction();

            foreach ($request->etapaActualizada as $leadData) {
                Lead::where('id', $leadData['id_lead'])
                    ->where('id_user', $user->id)
                    ->update(['orden' => $leadData['nuevo_orden']]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Orden interno actualizado correctamente',
                'leads_actualizados' => count($request->etapaActualizada)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            logger()->error('Error en actualizarOrdenInterno: ' . $e->getMessage());
            return response()->json(['error' => 'Error al actualizar orden: ' . $e->getMessage()], 500);
        }
    }
}
