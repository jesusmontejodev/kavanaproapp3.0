<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\User;

class AdminLeadAnalisisController extends Controller
{
    //
    public function index(){}

    public function show( $id_user ){
        //Leads del usuario
        $leadsUsuario = Lead::where( 'id_user', $id_user );

        dd($leadsUsuario);
    }
}
