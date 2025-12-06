<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoTarea extends Model
{
    protected $table = 'grupo_tareas';

    protected $fillable = [
        'nombre'
    ];

    public function proyecto()
    {
        return $this->belongsTo(User::class, 'id_proyecto');
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'id_grupo_tarea');
    }

}
