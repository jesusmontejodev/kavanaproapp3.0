<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_lead',
        'nombre_completo',
        'email',
        'telefono',
        'inmueble_comprado',
        'precio_compra',
        'direccion_inmueble',
        'tipo_inmueble',
        'estado_entrega',
        'fecha_compra',
        'fecha_entrega_estimada',
        'fecha_entrega_real',
        'ultimo_seguimiento',
        'proximo_seguimiento',
        'observaciones_entrega',
        'notas'
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'fecha_compra' => 'date',
        'fecha_entrega_estimada' => 'date',
        'fecha_entrega_real' => 'date',
        'proximo_seguimiento' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'id_lead');
    }

    // Métodos para el proceso de entrega
    public function getEstadoEntregaTextoAttribute()
    {
        return [
            'contrato_firmado' => 'Contrato Firmado',
            'proceso_escrituras' => 'Proceso de Escrituras',
            'avance_obra' => 'Avance de Obra',
            'ultimos_detalles' => 'Últimos Detalles',
            'entrega_finalizada' => 'Entrega Finalizada'
        ][$this->estado_entrega] ?? 'No especificado';
    }

    public function getProgresoEntregaAttribute()
    {
        $progreso = [
            'contrato_firmado' => 20,
            'proceso_escrituras' => 40,
            'avance_obra' => 70,
            'ultimos_detalles' => 90,
            'entrega_finalizada' => 100
        ];

        return $progreso[$this->estado_entrega] ?? 0;
    }

    public function getPrecioCompraFormateadoAttribute()
    {
        // Verificar si precio_compra no es null
        if ($this->precio_compra === null) {
            return 'No especificado';
        }

        return '$' . number_format((float) $this->precio_compra, 2);
    }

    public function necesitaSeguimiento()
    {
        return $this->estado_entrega !== 'entrega_finalizada' &&
            $this->proximo_seguimiento &&
            $this->proximo_seguimiento <= now()->addDays(7);
    }

    // Método para verificar si está próximo a entregarse
    public function getEstaProximoAEntregarAttribute()
    {
        return $this->fecha_entrega_estimada &&
            $this->fecha_entrega_estimada <= now()->addDays(30);
    }
}
