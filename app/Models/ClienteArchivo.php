<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteArchivo extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'id_cliente',
        'url_archivo',
        'nombre_archivo'
    ];


}
