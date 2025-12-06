<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminLeadsController extends Controller
{
    //
    public function index(){
        echo "Convertir leads en clientes";
        echo "<div>";

            echo"<h3>Leads de Jesus Montejo</h3>";
            echo"<ul>";
                echo"<li>Lead 1 <button>Convertir a cliente</button></li>";
                echo"<li>Lead 2 <button>Convertir a cliente</button></li>";
                echo"<li>Lead 3 <button>Convertir a cliente</button></li>";
            echo"</ul>";

        echo "</div>";
    }








}
