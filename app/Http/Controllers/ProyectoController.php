<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Embudo;
use App\Models\MediaProyecto;
use App\Models\LinkProyecto;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class ProyectoController extends Controller
{

    public function index()
    {

        // Cargar proyectos CON sus grupos de tareas (usa with para eager loading)
        $proyectos = Proyecto::get();

        //return $proyectos;
        return view('adminproyectos.index', compact('proyectos'));
    }

    public function show($id)
    {
        $proyecto = Proyecto::find($id);
        $mediasProyecto = MediaProyecto::where('id_proyecto', $id)->get();
        $linksProyecto = LinkProyecto::where('id_proyecto', $id)->get();


        return view('adminproyectos.show', compact('proyecto', 'mediasProyecto', 'linksProyecto'));
    }

    public function edit(){

    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'link_proyecto' => 'nullable|url',
            'url_imagen' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $ruta_final = "";

        if ($request->hasFile('url_imagen')) {
            $imagen = $request->file('url_imagen');
            $nombreImagen = time() . '_' . uniqid() . '.webp'; // Usar webp para mejor compresión

            // ✅ REDIMENSIONAR Y OPTIMIZAR
            $this->optimizarImagen($imagen, $nombreImagen);

            $ruta_final = 'proyectos/' . $nombreImagen;
        }

        Proyecto::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'link_proyecto' => $validated['link_proyecto'],
            'url_imagen' => $ruta_final
        ]);

        return redirect()->route('adminproyectos.index')
            ->with('success', 'Proyecto creado exitosamente!');
    }


    public function destroy($id)
    {
        $proyecto = Proyecto::findOrFail($id);

        $proyecto->delete();

        return redirect()->route('adminproyectos.index')
                        ->with('success', 'Proyecto eliminado correctamente!');
    }


    private function optimizarImagen($imagen, $nombreImagen)
    {
        $manager = new ImageManager(new Driver());

        // Crear instancia de la imagen
        $image = $manager->read($imagen->getRealPath());

        // Redimensionar manteniendo aspect ratio
        $image->scaleDown(1200, 800); // Máximo 1200x800px

        // Mejorar calidad y comprimir
        $image->toWebp(75); // 75% calidad (balance calidad/tamaño)

        // Guardar imagen optimizada
        $image->save(public_path('proyectos/' . $nombreImagen));
    }
}
