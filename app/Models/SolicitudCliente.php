<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudCliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_lead',
        'descripcion_solicitud',
        'estado',
        'comentario_admin',
        'fecha_revision'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'id_lead');
    }

    // Scope para fácil filtrado
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }
}
