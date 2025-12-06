<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referido extends Model
{
    protected $table = 'referidos';

    protected $fillable = [
        'coordinador_id',
        'referido_id'
    ];

    public function coordinador()
    {
        return $this->belongsTo(User::class, 'coordinador_id');
    }

    public function referido()
    {
        return $this->belongsTo(User::class, 'referido_id');
    }
}
