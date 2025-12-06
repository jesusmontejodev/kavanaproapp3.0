<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PDO;

class MediaProyecto extends Model
{
    //
    protected $fillable = [
        'id_proyecto',
        'url_imagen',
        'descripcion',
    ];

    public function proyecto(){
        return $this->belongsTo(Proyecto::class, 'id_proyecto');

    }
}
