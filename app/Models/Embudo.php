<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Embudo extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    public function etapas()
    {
        return $this->hasMany(Etapa::class, 'id_embudo');
    }

    public function leads()
    {
        return $this->hasManyThrough(
            Lead::class,
            Etapa::class,
            'id_embudo',
            'id_etapa',
            'id',
            'id'
        );
    }
}
