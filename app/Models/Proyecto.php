<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{


    protected $fillable = [
        'nombre',
        'descripcion',
        'link_proyecto',
        'url_imagen'
    ];

    function mediaProyectos()
    {
        return $this->hasMany(MediaProyecto::class, 'id_proyecto');
    }
    function linkProyectos()
    {
        return $this->hasMany(LinkProyecto::class, 'id_proyecto');
    }
}
