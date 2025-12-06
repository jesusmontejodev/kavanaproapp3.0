<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\SolicitudCliente;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;

class MisClientesController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $leads = Lead::where('id_user', $userId)->get();
        $misSolicitudes = SolicitudCliente::with(['lead'])
            ->where('id_user', $userId)
            ->latest()
            ->get();

        // Obtener los clientes del usuario actual
        $misClientes = Cliente::where('id_user', $userId)
            ->latest()
            ->get();

        return view('misclientes.index', compact('leads', 'misSolicitudes', 'misClientes'));
    }
}
