<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Proyecto;
use App\Models\Embudo;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class EmbudoController extends Controller
{
    public function index()
    {

    }
    public function show($id)
    {
        $embudo = Embudo::with('etapas')->find($id);

        if (!$embudo) {
            abort(404);
        }

        // Obtener los IDs de las etapas de este embudo
        $etapaIds = $embudo->etapas->pluck('id');

        // Obtener leads del usuario para las etapas de este embudo
        $leads = Lead::where('id_user', Auth::id())
                    ->whereIn('id_etapa', $etapaIds)
                    ->with('etapa') // Cargar la relación etapa
                    ->get();

        $user = Auth::user();
        $token = $user->createToken('FrontendToken')->plainTextToken;

        return view('embudos.show', compact(
            'embudo',
            'leads',
            'token'
        ));
    }


}
