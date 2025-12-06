<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\MediaProyecto;
use App\Models\LinkProyecto;


class ProyectoUsuarioController extends Controller
{
    //
    public function show( $id){
        $proyecto = Proyecto::find($id);
        $mediasProyecto = MediaProyecto::where('id_proyecto', $id)->get();
        $linksProyecto = LinkProyecto::where('id_proyecto', $id)->get();

        return view('proyectos.show', compact('proyecto', 'mediasProyecto', 'linksProyecto'));

    }
}
