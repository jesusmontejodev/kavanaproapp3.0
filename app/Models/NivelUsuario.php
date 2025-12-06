<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NivelUsuario extends Model
{
    protected $fillable = [
        'id_user',
        'nivel',
        'link_referido',
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'id_user');
    }

}
