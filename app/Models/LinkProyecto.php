<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkProyecto extends Model
{
    //
    protected $fillable = [
        'id_proyecto',
        'url_archivo',
        'descripcion',
    ];

    public function proyecto(){
        return $this->belongsTo(Proyecto::class, 'id_proyecto');
    }
}
