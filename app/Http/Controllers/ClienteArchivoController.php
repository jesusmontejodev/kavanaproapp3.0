<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ClienteArchivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClienteArchivoController extends Controller
{
    public function store(Request $request)
    {
        // Validar
        $request->validate([
            'id_cliente' => 'required|exists:clientes,id',
            'archivo' => 'required|file|max:51200', // 50MB máximo
        ]);

        // Verificar que el cliente pertenece al usuario
        $userId = Auth::id();
        $cliente = Cliente::where('id', $request->id_cliente)
            ->where('id_user', $userId)
            ->firstOrFail();

        // Procesar archivo
        $archivo = $request->file('archivo');
        $nombreOriginal = $archivo->getClientOriginalName();

        // Generar nombre único para evitar colisiones
        $nombreArchivo = Str::random(20) . '_' . time() . '.' . $archivo->getClientOriginalExtension();

        // Guardar en storage privado (storage/app/private/)
        $ruta = $archivo->storeAs('clientes/' . $cliente->id, $nombreArchivo, 'local');

        // Guardar en base de datos (simple, sin id_user)
        $clienteArchivo = ClienteArchivo::create([
            'id_cliente' => $cliente->id,
            'nombre_archivo' => $nombreOriginal,
            'url_archivo' => $ruta,
        ]);

        return back()->with('success', 'Archivo subido exitosamente');
    }

    /**
     * Descargar archivo - VERSIÓN CORREGIDA
     */
    public function download($id)
    {
        try {
            $archivo = ClienteArchivo::with('cliente')->findOrFail($id);

            // Verificar permisos
            if (!$archivo->usuarioPuedeAcceder(Auth::id())) {
                abort(403, 'No tienes permiso para acceder a este archivo.');
            }

            // Verificar que el archivo existe
            if (!$archivo->archivoExiste()) {
                // En lugar de abortar, redirigir con mensaje de error
                return redirect()->back()
                    ->with('error', 'El archivo "' . $archivo->nombre_archivo . '" no se encuentra en el servidor.');

                // Opción: Si quieres eliminar el registro automáticamente
                // $archivo->delete();
                // return redirect()->back()
                //     ->with('warning', 'El archivo no existía y ha sido eliminado de los registros.');
            }

            // Obtener la ruta de storage
            $rutaStorage = $archivo->url_archivo;

            // Verificar que existe usando Storage
            if (!Storage::disk('local')->exists($rutaStorage)) {
                throw new \Exception('El archivo no existe en el almacenamiento');
            }

            // Usar Storage::download() en lugar de response()->download()
            // Esto maneja mejor los archivos de Laravel Storage
            return Storage::disk('local')->download(
                $rutaStorage,
                $archivo->nombre_archivo
            );

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Archivo no encontrado en la base de datos.');
        } catch (\Exception $e) {
            // Si hay un error, redirigir con mensaje
            return redirect()->back()
                ->with('error', 'Error al descargar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Ver archivo en el navegador (si es posible) - VERSIÓN CORREGIDA
     */
    public function ver($id)
    {
        try {
            $archivo = ClienteArchivo::with('cliente')->findOrFail($id);

            // Verificar permisos
            if (!$archivo->usuarioPuedeAcceder(Auth::id())) {
                abort(403, 'No tienes permiso para acceder a este archivo.');
            }

            // Verificar que el archivo existe
            if (!$archivo->archivoExiste()) {
                return redirect()->back()
                    ->with('error', 'El archivo "' . $archivo->nombre_archivo . '" no se encuentra en el servidor.');
            }

            // Solo permitir visualización para ciertos tipos de archivos
            if (!$archivo->es_visualizable) {
                return $this->download($id);
            }

            // Obtener contenido del archivo usando Storage
            $rutaStorage = $archivo->url_archivo;
            $contenido = Storage::disk('local')->get($rutaStorage);
            $mime = Storage::disk('local')->mimeType($rutaStorage);

            return response($contenido, 200)
                ->header('Content-Type', $mime)
                ->header('Content-Disposition', 'inline; filename="' . $archivo->nombre_archivo . '"');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al visualizar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar archivo
     */
    public function destroy($id)
    {
        $archivo = ClienteArchivo::with('cliente')->findOrFail($id);

        // Verificar permisos
        if (!$archivo->usuarioPuedeEliminar(Auth::id())) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para eliminar este archivo.'
            ], 403);
        }

        try {
            // Eliminar archivo físico si existe
            if ($archivo->archivoExiste()) {
                Storage::disk('local')->delete($archivo->url_archivo);
            }

            // Eliminar registro de la base de datos
            $archivo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Archivo eliminado exitosamente.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener archivos de un cliente (para AJAX)
     */
    public function index($clienteId)
    {
        $userId = Auth::id();

        // Verificar que el cliente pertenece al usuario
        $cliente = Cliente::where('id', $clienteId)
            ->where('id_user', $userId)
            ->firstOrFail();

        // Obtener archivos con paginación
        $archivos = ClienteArchivo::where('id_cliente', $clienteId)
            ->latest()
            ->paginate(10);

        // Transformar datos para la vista
        $archivosTransformados = $archivos->map(function ($archivo) {
            return [
                'id' => $archivo->id,
                'nombre' => $archivo->nombre_archivo,
                'tamano' => $archivo->tamano_formateado,
                'fecha' => $archivo->created_at->format('d/m/Y H:i'),
                'fecha_iso' => $archivo->created_at->toISOString(),
                'icono' => $archivo->icono,
                'color' => $archivo->color,
                'extension' => $archivo->extension,
                'es_visualizable' => $archivo->es_visualizable,
                'urls' => [
                    'ver' => route('cliente.archivos.ver', $archivo->id),
                    'descargar' => route('cliente.archivos.download', $archivo->id),
                    'eliminar' => route('cliente.archivos.destroy', $archivo->id),
                ],
                'permisos' => [
                    'puede_eliminar' => $archivo->usuarioPuedeEliminar(Auth::id()),
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'archivos' => [
                'data' => $archivosTransformados,
                'current_page' => $archivos->currentPage(),
                'last_page' => $archivos->lastPage(),
                'per_page' => $archivos->perPage(),
                'total' => $archivos->total(),
                'from' => $archivos->firstItem(),
                'to' => $archivos->lastItem(),
            ]
        ]);
    }
}
