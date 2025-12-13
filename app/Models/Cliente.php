<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'ultimo_seguimiento',  // Este es tipo TEXT en la BD
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
        // 'ultimo_seguimiento' => 'datetime', // <-- ELIMINAR o COMENTAR esta línea
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'id_lead');
    }

    public function archivos()
    {
        return $this->hasMany(ClienteArchivo::class, 'id_cliente');
    }

    // Accessors para ultimo_seguimiento (si necesitas manejarlo como fecha)
    public function getUltimoSeguimientoFechaAttribute()
    {
        // Intentar parsear el texto como fecha si contiene una fecha
        if ($this->ultimo_seguimiento) {
            // Buscar patrones de fecha en el texto
            preg_match('/(\d{4}-\d{2}-\d{2})/', $this->ultimo_seguimiento, $matches);
            if (!empty($matches[1])) {
                return Carbon::parse($matches[1]);
            }
        }
        return null;
    }

    public function getUltimoSeguimientoTextoAttribute()
    {
        return $this->ultimo_seguimiento ?: 'Sin seguimiento registrado';
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

    public function getEstaProximoAEntregarAttribute()
    {
        return $this->fecha_entrega_estimada &&
            $this->fecha_entrega_estimada <= now()->addDays(30);
    }
}
