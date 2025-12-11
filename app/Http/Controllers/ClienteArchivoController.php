<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClienteArchivo;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClienteArchivoController extends Controller
{
    // Usar el disco 'local' que apunta a storage/app/private/
    private $discoPrivado = 'local';

    /**
     * Subir archivo privado para un cliente
     */
    public function store(Request $request)
    {
        // Validar
        $validated = $request->validate([
            'id_cliente' => 'required|exists:clientes,id',
            'archivo' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,txt|max:51200', // 50MB
            'descripcion' => 'nullable|string|max:500',
            'categoria' => 'nullable|string|max:100',
        ]);

        // Verificar que el cliente pertenece al usuario
        $userId = Auth::id();
        $cliente = Cliente::where('id', $validated['id_cliente'])
            ->where('id_user', $userId)
            ->firstOrFail();

        // Procesar archivo
        $archivo = $request->file('archivo');
        $nombreOriginal = $archivo->getClientOriginalName();
        $extension = $archivo->getClientOriginalExtension();
        $mimeType = $archivo->getMimeType();
        $tamano = $archivo->getSize();

        // Generar hash único y seguro
        $nombreHash = $this->generarHashSeguro($cliente->id) . '.' . $extension;

        // Ruta donde se guardará dentro de storage/app/private/
        $rutaArchivo = 'clientes/' . $cliente->id . '/' . $nombreHash;

        // Crear directorio si no existe
        $directorio = dirname($rutaArchivo);
        if (!Storage::disk($this->discoPrivado)->exists($directorio)) {
            Storage::disk($this->discoPrivado)->makeDirectory($directorio);
        }

        // Guardar archivo en storage privado
        Storage::disk($this->discoPrivado)->put(
            $rutaArchivo,
            file_get_contents($archivo->getRealPath())
        );

        // Guardar registro en BD
        $archivoDB = ClienteArchivo::create([
            'id_cliente' => $cliente->id,
            'id_user' => $userId,
            'nombre_archivo' => $nombreOriginal,
            'nombre_hash' => pathinfo($nombreHash, PATHINFO_FILENAME),
            'ruta_archivo' => $rutaArchivo,
            'extension' => $extension,
            'tamano' => $tamano,
            'mime_type' => $mimeType,
            'descripcion' => $validated['descripcion'] ?? null,
            'categoria' => $validated['categoria'] ?? 'general',
            'ip_subida' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Archivo subido exitosamente',
            'data' => $this->formatearArchivo($archivoDB)
        ], 201);
    }

    /**
     * Servir archivo para visualización (imágenes, PDFs)
     */
    public function servir($hash)
    {
        // Buscar archivo por hash
        $archivo = ClienteArchivo::where('nombre_hash', $hash)->firstOrFail();

        // Verificar permisos
        $this->verificarPermisosArchivo($archivo);

        // Verificar que el archivo existe físicamente
        if (!Storage::disk($this->discoPrivado)->exists($archivo->ruta_archivo)) {
            abort(404, 'El archivo no existe en el servidor');
        }

        // Obtener contenido del archivo
        $contenido = Storage::disk($this->discoPrivado)->get($archivo->ruta_archivo);

        // Determinar disposición según tipo MIME
        $esVisualizable = in_array($archivo->mime_type, [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            'application/pdf', 'text/plain'
        ]);

        $disposition = $esVisualizable ? 'inline' : 'attachment';

        return response($contenido)
            ->header('Content-Type', $archivo->mime_type)
            ->header('Content-Disposition', $disposition . '; filename="' . $archivo->nombre_archivo . '"')
            ->header('Cache-Control', 'private, max-age=3600')
            ->header('X-Content-Type-Options', 'nosniff');
    }

    /**
     * Forzar descarga del archivo
     */
    public function descargar($hash)
    {
        $archivo = ClienteArchivo::where('nombre_hash', $hash)->firstOrFail();

        // Verificar permisos
        $this->verificarPermisosArchivo($archivo);

        // Verificar que existe
        if (!Storage::disk($this->discoPrivado)->exists($archivo->ruta_archivo)) {
            abort(404, 'Archivo no encontrado');
        }

        // Usar download() para forzar descarga
        return Storage::disk($this->discoPrivado)->download(
            $archivo->ruta_archivo,
            $archivo->nombre_archivo
        );
    }

    /**
     * Listar archivos de un cliente
     */
    public function index($clienteId)
    {
        // Verificar que el cliente pertenece al usuario
        $userId = Auth::id();
        $cliente = Cliente::where('id', $clienteId)
            ->where('id_user', $userId)
            ->firstOrFail();

        // Obtener archivos paginados
        $archivos = ClienteArchivo::where('id_cliente', $clienteId)
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->through(function($archivo) {
                return $this->formatearArchivo($archivo);
            });

        // Estadísticas de espacio
        $espacioUtilizado = ClienteArchivo::where('id_cliente', $clienteId)->sum('tamano');
        $limiteBytes = 100 * 1024 * 1024; // 100MB límite por cliente

        return response()->json([
            'success' => true,
            'cliente' => [
                'id' => $cliente->id,
                'nombre' => $cliente->nombre_completo,
            ],
            'archivos' => $archivos,
            'estadisticas' => [
                'total' => $archivos->total(),
                'por_pagina' => $archivos->perPage(),
                'pagina_actual' => $archivos->currentPage(),
                'ultima_pagina' => $archivos->lastPage(),
            ],
            'espacio' => [
                'utilizado' => $this->formatearBytes($espacioUtilizado),
                'utilizado_bytes' => $espacioUtilizado,
                'disponible' => $this->formatearBytes($limiteBytes - $espacioUtilizado),
                'porcentaje' => round(($espacioUtilizado / $limiteBytes) * 100, 2),
                'limite' => $this->formatearBytes($limiteBytes),
            ]
        ]);
    }

    /**
     * Eliminar archivo
     */
    public function destroy($hash)
    {
        $archivo = ClienteArchivo::where('nombre_hash', $hash)->firstOrFail();

        // Solo el usuario que subió el archivo puede eliminarlo
        if ($archivo->id_user != Auth::id()) {
            abort(403, 'Solo puedes eliminar tus propios archivos');
        }

        // Eliminar archivo físico
        if (Storage::disk($this->discoPrivado)->exists($archivo->ruta_archivo)) {
            Storage::disk($this->discoPrivado)->delete($archivo->ruta_archivo);
        }

        // Eliminar registro de BD
        $archivo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Archivo eliminado exitosamente'
        ]);
    }

    /**
     * Obtener información de un archivo específico
     */
    public function show($hash)
    {
        $archivo = ClienteArchivo::where('nombre_hash', $hash)->firstOrFail();

        // Verificar permisos
        $this->verificarPermisosArchivo($archivo);

        return response()->json([
            'success' => true,
            'data' => $this->formatearArchivo($archivo)
        ]);
    }

    /**
     * Actualizar información del archivo (descripción, categoría)
     */
    public function update(Request $request, $hash)
    {
        $archivo = ClienteArchivo::where('nombre_hash', $hash)->firstOrFail();

        // Solo el dueño puede actualizar
        if ($archivo->id_user != Auth::id()) {
            abort(403, 'Solo puedes modificar tus propios archivos');
        }

        $validated = $request->validate([
            'descripcion' => 'nullable|string|max:500',
            'categoria' => 'nullable|string|max:100',
        ]);

        $archivo->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Archivo actualizado',
            'data' => $this->formatearArchivo($archivo->fresh())
        ]);
    }

    /**
     * ===== MÉTODOS PRIVADOS DE AYUDA =====
     */

    private function verificarPermisosArchivo($archivo)
    {
        $userId = Auth::id();

        if (!$userId) {
            abort(401, 'Debes iniciar sesión para acceder a archivos');
        }

        // El usuario puede acceder si:
        // 1. Es el que subió el archivo
        // 2. Es el dueño del cliente
        $tienePermiso = $archivo->id_user == $userId ||
                       ($archivo->cliente && $archivo->cliente->id_user == $userId);

        if (!$tienePermiso) {
            abort(403, 'No tienes permiso para acceder a este archivo');
        }
    }

    private function generarHashSeguro($clienteId)
    {
        // Generar hash único e imposible de predecir
        return hash('sha256',
            Str::random(40) .
            microtime(true) .
            $clienteId .
            Auth::id() .
            mt_rand(10000, 99999)
        );
    }

    private function formatearArchivo($archivo)
    {
        return [
            'id' => $archivo->id,
            'hash' => $archivo->nombre_hash,
            'nombre' => $archivo->nombre_archivo,
            'extension' => $archivo->extension,
            'tamano' => $this->formatearBytes($archivo->tamano),
            'tamano_bytes' => $archivo->tamano,
            'mime_type' => $archivo->mime_type,
            'descripcion' => $archivo->descripcion,
            'categoria' => $archivo->categoria,
            'fecha_subida' => $archivo->created_at->format('d/m/Y H:i'),
            'fecha_actualizacion' => $archivo->updated_at->format('d/m/Y H:i'),
            'icono' => $this->obtenerIcono($archivo->extension),
            'tipo_archivo' => $this->obtenerTipoArchivo($archivo->mime_type),
            'es_visualizable' => $this->esVisualizable($archivo->mime_type),
            'urls' => [
                'ver' => route('cliente.archivos.servir', $archivo->nombre_hash),
                'descargar' => route('cliente.archivos.descargar', $archivo->nombre_hash),
                'info' => route('cliente.archivos.show', $archivo->nombre_hash),
            ],
            'subido_por' => [
                'id' => $archivo->usuario->id ?? null,
                'nombre' => $archivo->usuario->name ?? 'Usuario',
            ],
            'cliente' => [
                'id' => $archivo->cliente->id ?? null,
                'nombre' => $archivo->cliente->nombre_completo ?? 'Cliente',
            ],
            'permisos' => [
                'puede_eliminar' => $archivo->id_user == Auth::id(),
                'puede_actualizar' => $archivo->id_user == Auth::id(),
            ]
        ];
    }

    private function formatearBytes($bytes)
    {
        if ($bytes == 0) return '0 B';

        $unidades = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes, 1024));

        return round($bytes / pow(1024, $i), 2) . ' ' . $unidades[$i];
    }

    private function obtenerIcono($extension)
    {
        $iconos = [
            'pdf' => 'fas fa-file-pdf',
            'jpg' => 'fas fa-file-image',
            'jpeg' => 'fas fa-file-image',
            'png' => 'fas fa-file-image',
            'gif' => 'fas fa-file-image',
            'webp' => 'fas fa-file-image',
            'doc' => 'fas fa-file-word',
            'docx' => 'fas fa-file-word',
            'xls' => 'fas fa-file-excel',
            'xlsx' => 'fas fa-file-excel',
            'txt' => 'fas fa-file-alt',
            'zip' => 'fas fa-file-archive',
            'rar' => 'fas fa-file-archive',
        ];

        return $iconos[strtolower($extension)] ?? 'fas fa-file';
    }

    private function obtenerTipoArchivo($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'imagen';
        } elseif (str_starts_with($mimeType, 'application/pdf')) {
            return 'documento';
        } elseif (str_starts_with($mimeType, 'application/msword') ||
                str_starts_with($mimeType, 'application/vnd.openxmlformats-officedocument')) {
            return 'documento';
        } elseif (str_starts_with($mimeType, 'text/')) {
            return 'texto';
        } else {
            return 'archivo';
        }
    }

    private function esVisualizable($mimeType)
    {
        return in_array($mimeType, [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            'application/pdf', 'text/plain', 'text/html'
        ]);
    }
}
