<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Departamento;
use App\Models\Rol;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'usuario_id';
    public $incrementing = true;
    protected $keyType = 'int';
    // Deshabilita el manejo automático de timestamps por parte de Laravel
    public $timestamps = false;


    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password_hash',
        'departamento_id',
        'activo',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token', // Asegúrate de que esta columna exista en tu DB si la tienes aquí
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',      // Esta columna seguirá siendo tratada como datetime al leer
        // 'fecha_actualizacion' => 'datetime', // ¡Comenta o quita esta línea si no existe la columna!
        'password_hash' => 'hashed',
        'activo' => 'boolean',
    ];

    public function getAuthPasswordName()
    {
        return 'password_hash';
    }

    /**
     * Relación con Roles (Muchos a Muchos)
     */
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'usuario_roles', 'usuario_id', 'rol_id')
                     ->withPivot('fecha_asignacion');
    }

    /**
     * Relación con Departamento (Muchos a Uno)
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id', 'departamento_id');
    }

    // --- Métodos para verificar roles ---
    public function hasRole($roleName)
    {
        return $this->roles->contains('nombre_rol', $roleName);
    }

    public function hasAnyRole($roleNames)
    {
        if (!is_array($roleNames)) {
            $roleNames = [$roleNames];
        }
        return $this->roles->whereIn('nombre_rol', $roleNames)->isNotEmpty();
    }

    public function isAdministrador()
    {
        return $this->hasRole('Administrador');
    }

    public function isDocente()
    {
        return $this->hasRole('Docente');
    }

    public function isCoordinador()
    {
        return $this->hasRole('Coordinador');
    }

    public function isRector()
    {
        return $this->hasRole('Rector');
    }
}