<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsuarioTarea extends Model
{
    // Si tu tabla tiene otro nombre, especifícalo aquí
    protected $table = 'usuario_tareas'; // o el nombre que uses

    protected $fillable = [
        'id_tarea',
        'id_user',
        'completado',
        'fecha_completado'
    ];

    /**
     * Relación con el usuario
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Relación con la tarea
     */
    public function tarea(): BelongsTo
    {
        return $this->belongsTo(Tarea::class, 'id_tarea');
    }

    /**
     * Scope para tareas completadas
     */
    public function scopeCompletadas($query)
    {
        return $query->where('completado', true);
    }

    /**
     * Scope para tareas pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('completado', false);
    }

    /**
     * Marcar tarea como completada
     */
    public function marcarComoCompletada()
    {
        $this->update([
            'completado' => true,
            'fecha_completado' => now()
        ]);
    }

    /**
     * Marcar tarea como pendiente
     */
    public function marcarComoPendiente()
    {
        $this->update([
            'completado' => false,
            'fecha_completado' => null
        ]);
    }
}
