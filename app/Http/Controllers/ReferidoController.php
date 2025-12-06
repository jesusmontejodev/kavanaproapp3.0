<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ReferidoController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user->roles()->exists()) {
            abort(403, 'No tienes permisos para generar referidos.');
        }

        $linkReferido = url('/register/') . '/' . $user->id;

        // 🔥 Obtener mis referidos
        $misReferidos = $user->referidos()->with('referido')->get();

        return view('referidos.index', compact('linkReferido', 'misReferidos'));
    }
}
