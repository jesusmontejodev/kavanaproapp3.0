<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ClienteArchivo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClienteUsuarioAdminController extends Controller
{
    public function show($id_usuario)
    {
        $clientes = Cliente::where('id_user', $id_usuario)->get();
        $usuario = User::findOrFail($id_usuario);

        return view('admin.usuarios.clientes', compact('clientes', 'usuario'));
    }

    public function archivosclienteusuario($id_cliente)
    {
        $archivos = ClienteArchivo::where('id_cliente', $id_cliente)->get();
        $cliente = Cliente::findOrFail($id_cliente);

        return view('admin.usuarios.clientes.archivos', compact('archivos', 'cliente'));
    }

    /**
     * Subir nuevo archivo - SIN VERIFICACIONES
     */
    public function store(Request $request)
    {
        // Validar
        $request->validate([
            'id_cliente' => 'required|exists:clientes,id',
            'archivo' => 'required|file|max:51200', // 50MB máximo
        ]);

        // Obtener cliente
        $cliente = Cliente::findOrFail($request->id_cliente);

        // Procesar archivo
        $archivo = $request->file('archivo');
        $nombreOriginal = $archivo->getClientOriginalName();

        // Generar nombre único
        $nombreArchivo = Str::random(20) . '_' . time() . '.' . $archivo->getClientOriginalExtension();

        // Guardar en storage
        $ruta = $archivo->storeAs('clientes/' . $cliente->id, $nombreArchivo, 'local');

        // Guardar en base de datos
        $clienteArchivo = ClienteArchivo::create([
            'id_cliente' => $cliente->id,
            'nombre_archivo' => $nombreOriginal,
            'url_archivo' => $ruta,
        ]);

        return back()->with('success', 'Archivo subido exitosamente');
    }

    /**
     * Subir archivo desde vista - SIN VERIFICACIONES
     */
    public function subirNuevoArchiv(Request $request, $id_cliente)
    {
        // Validar
        $request->validate([
            'archivo' => 'required|file|max:51200',
        ]);

        // Obtener cliente
        $cliente = Cliente::findOrFail($id_cliente);

        // Procesar archivo
        $archivo = $request->file('archivo');
        $nombreOriginal = $archivo->getClientOriginalName();

        // Generar nombre único
        $nombreArchivo = Str::random(20) . '_' . time() . '.' . $archivo->getClientOriginalExtension();

        // Guardar en storage
        $ruta = $archivo->storeAs('clientes/' . $cliente->id, $nombreArchivo, 'local');

        // Guardar en base de datos
        $clienteArchivo = ClienteArchivo::create([
            'id_cliente' => $cliente->id,
            'nombre_archivo' => $nombreOriginal,
            'url_archivo' => $ruta,
        ]);

        return back()->with('success', 'Archivo subido exitosamente');
    }

    /**
     * Descargar archivo - SIN VERIFICACIONES
     */
    public function download($id)
    {
        try {
            $archivo = ClienteArchivo::with('cliente')->findOrFail($id);

            // Verificar que el archivo existe
            if (!Storage::disk('local')->exists($archivo->url_archivo)) {
                return back()->with('error', 'El archivo no se encuentra en el servidor.');
            }

            // Descargar archivo
            return Storage::disk('local')->download(
                $archivo->url_archivo,
                $archivo->nombre_archivo
            );

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Archivo no encontrado.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al descargar el archivo.');
        }
    }

    /**
     * Ver archivo en navegador - SIN VERIFICACIONES
     */
    public function ver($id)
    {
        try {
            $archivo = ClienteArchivo::with('cliente')->findOrFail($id);

            // Verificar que el archivo existe
            if (!Storage::disk('local')->exists($archivo->url_archivo)) {
                return back()->with('error', 'El archivo no se encuentra en el servidor.');
            }

            // Obtener contenido y tipo MIME
            $contenido = Storage::disk('local')->get($archivo->url_archivo);
            $mime = Storage::disk('local')->mimeType($archivo->url_archivo);

            return response($contenido, 200)
                ->header('Content-Type', $mime)
                ->header('Content-Disposition', 'inline; filename="' . $archivo->nombre_archivo . '"');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al visualizar el archivo.');
        }
    }

    /**
     * Eliminar archivo - SIN VERIFICACIONES
     */
    public function deleteArchivo($id_archivo)
    {
        try {
            $archivo = ClienteArchivo::findOrFail($id_archivo);

            // Eliminar archivo físico si existe
            if (Storage::disk('local')->exists($archivo->url_archivo)) {
                Storage::disk('local')->delete($archivo->url_archivo);
            }

            // Eliminar registro
            $archivo->delete();

            return back()->with('success', 'Archivo eliminado exitosamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el archivo.');
        }
    }

    /**
     * Eliminar archivo por AJAX - SIN VERIFICACIONES
     */
    public function destroy($id)
    {
        try {
            $archivo = ClienteArchivo::findOrFail($id);

            // Eliminar archivo físico si existe
            if (Storage::disk('local')->exists($archivo->url_archivo)) {
                Storage::disk('local')->delete($archivo->url_archivo);
            }

            // Eliminar registro
            $archivo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Archivo eliminado exitosamente.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el archivo.'
            ], 500);
        }
    }
}
