<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'id_user',
        'id_etapa',
        'orden',
        'nombre',
        'correo',
        'numero_telefono',
        'fecha_creado',
        'nombre_proyecto'
    ];

    // Hacer nullable
    protected $casts = [
        'id_etapa' => 'integer',
        'fecha_creado' => 'datetime',
    ];

    public function usuario() {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function etapa() {
        return $this->belongsTo(Etapa::class, 'id_etapa');
    }

    // Relación al embudo a través de la etapa
    public function embudo() {
        return $this->hasOneThrough(
            Embudo::class,
            Etapa::class,
            'id',
            'id',
            'id_etapa',
            'id_embudo'
        );
    }

    // Accesor para obtener el embudo fácilmente
    public function getEmbudoAttribute() {
        return $this->etapa ? $this->etapa->embudo : null;
    }

    public function solicitudes()
    {
        return $this->hasMany(SolicitudCliente::class, 'id_lead');
    }
    // En App\Models\Lead.php
    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'id_lead');
    }
}
