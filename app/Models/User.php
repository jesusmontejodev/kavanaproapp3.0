<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; // Agrega esta línea

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes; // Agrega SoftDeletes aquí

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'estado',
        'ciudad',
        'empresa',
        'foto_perfil'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class, 'id_user');
    }

    // NUEVAS RELACIONES
    public function usuarioTareas()
    {
        return $this->hasMany(UsuarioTarea::class, 'id_user');
    }

    public function tareasCompletadas()
    {
        return $this->belongsToMany(Tarea::class, 'usuario_tareas', 'id_user', 'id_tarea')
                    ->withPivot('completado', 'fecha_completado')
                    ->wherePivot('completado', true);
    }

    public function solicitudCliente()
    {
        return $this->hasMany(UsuarioTarea::class, 'id_user');
    }

    //GESTION DE ROLES
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function assignRole($role)
    {
        $role = Role::where('name', $role)->firstOrFail();
        return $this->roles()->syncWithoutDetaching([$role->id]);
    }

    public function removeRole($role)
    {
        $role = Role::where('name', $role)->firstOrFail();
        return $this->roles()->detach($role->id);
    }

    public function referidos()
    {
        return $this->hasMany(RegistroReferido::class, 'referido_por');
    }

    public function referidoPor()
    {
        return $this->hasOne(Referido::class, 'referido_id');
    }

    public function getRoleNames()
    {
        return $this->roles->pluck('name');
    }


    public function getMainRole()
    {
        return $this->roles->first()->name ?? null;
    }

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'user_id');
    }
}
