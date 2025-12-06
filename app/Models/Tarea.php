<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    //
    protected $fillable = [
        'id_grupo_tarea',
        'nombre',
        'descripcion',
        'orden',
    ];

    public function grupotarea()
    {
        return $this->belongsTo(GrupoTarea::class, 'id_grupo_tarea');
    }

    public function tareas_usuario()
    {
        return $this->hasMany(UsuarioTarea::class, 'id_tarea');
    }
}
