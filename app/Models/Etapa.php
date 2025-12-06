<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etapa extends Model
{
    //
    protected $fillable = [
        'id_embudo',
        'nombre',
        'orden',
        'descripcion',
    ];

    public function embudo()
    {
        return $this->belongsTo(Embudo::class, 'id_embudo');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'id_etapa');
    }
}
