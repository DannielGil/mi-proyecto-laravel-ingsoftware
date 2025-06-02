<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Si usas verificación de email
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
        'departamento_id',
        'activo',
        'usuario_id', // ¡Asegúrate de que esta línea esté aquí!
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relación uno a uno con el modelo Usuario (de tu tabla 'usuarios')
    // Esto vincula el usuario de autenticación de Laravel con tu usuario del sistema
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'usuario_id');
    }

    // Método auxiliar para verificar si el usuario autenticado es administrador
    public function isAdmin()
    {
        // Primero, comprueba si tiene un registro en la tabla 'usuarios'
        // y si ese usuario tiene el rol 'Administrador'
        return $this->usuario && $this->usuario->hasRole('Administrador');
    }

    // Método auxiliar para verificar si el usuario autenticado tiene un rol específico
    public function hasRole($roleName)
    {
        return $this->usuario && $this->usuario->hasRole($roleName);
    }
}