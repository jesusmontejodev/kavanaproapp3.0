<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroReferido extends Model
{
    protected $table = 'registro_referidos';

    protected $fillable = [
        'user_id',
        'referido_por',
    ];

    public function referido()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function coordinador()
    {
        return $this->belongsTo(User::class, 'referido_por');
    }
}
