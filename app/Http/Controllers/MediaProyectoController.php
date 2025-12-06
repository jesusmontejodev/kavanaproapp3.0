<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\MediaProyecto;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;



class MediaProyectoController extends Controller
{
    public function store(Request $request)
    {
        // Validación
        $validated = $request->validate([
            'id_proyecto' => 'required|exists:proyectos,id',
            'url_imagen'  => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'descripcion' => 'nullable|string'
        ]);

        // Asegurarse de que el archivo exista (por si)
        if (! $request->hasFile('url_imagen')) {
            return redirect()->back()->withErrors(['url_imagen' => 'No se encontró archivo de imagen.']);
        }

        try {
            // Procesar imagen y obtener ruta final
            $ruta_final = $this->procesarImagen($request->file('url_imagen'));

            // Crear registro en BD
            $mediaProyecto = MediaProyecto::create([
                'id_proyecto' => $validated['id_proyecto'],
                'url_imagen'  => $ruta_final,
                'descripcion' => $validated['descripcion'] ?? null
            ]);

            // Responder: redirigir de regreso con mensaje o devolver JSON
            // (elige lo que más convenga a tu flujo; aquí dejo ambas opciones comentadas)
            return redirect()->back()->with('success', 'Imagen subida correctamente.');

            // Si usas AJAX/JS cambia por:
            // return response()->json($mediaProyecto, 201);

        } catch (\Exception $e) {
            // Log por si hay problema en procesamiento/IO
            Log::error('Error subiendo mediaProyecto: '.$e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al procesar la imagen.']);
        }
    }

    private function procesarImagen($imagen)
    {
        $nombreImagen = time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();

        // Carpeta donde se guardarán
        $directorio = public_path('mediaproyectos');

        // Crear carpeta si no existe
        if (!file_exists($directorio)) {
            mkdir($directorio, 0755, true);
        }

        // Guardar imagen tal cual viene (sin optimizar)
        $imagen->move($directorio, $nombreImagen);

        // Retornar ruta relativa
        return 'mediaproyectos/' . $nombreImagen;
    }

      public function destroy($id)
        {
            try {
                // Buscar la imagen
                $media = MediaProyecto::findOrFail($id);

                // Guardar el ID del proyecto para redirigir después
                $proyectoId = $media->id_proyecto;

                // Eliminar el archivo físico si existe
                $this->eliminarArchivoFisico($media->url_imagen);

                // Eliminar el registro de la base de datos
                $media->delete();

                // Redirigir de vuelta al proyecto
                return redirect()->route('adminproyectos.show', $proyectoId)
                    ->with('success', '✅ Imagen eliminada correctamente');

            } catch (\Exception $e) {
                return redirect()->back()
                    ->with('error', '❌ Error al eliminar la imagen: ' . $e->getMessage());
            }
        }

        /**
         * Elimina el archivo físico del storage
         */
        private function eliminarArchivoFisico($rutaImagen)
        {
            // Si la ruta es relativa (empieza con /storage/ o /images/)
            if (strpos($rutaImagen, '/storage/') === 0) {
                // Convertir ruta pública a ruta de storage
                $rutaStorage = str_replace('/storage/', 'public/', $rutaImagen);

                if (Storage::exists($rutaStorage)) {
                    Storage::delete($rutaStorage);
                }
            }
            // Si la ruta es absoluta en public
            elseif (file_exists(public_path($rutaImagen))) {
                unlink(public_path($rutaImagen));
            }
        }
}
