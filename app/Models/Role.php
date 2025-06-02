<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;


    protected $primaryKey = 'rol_id';


    protected $fillable = [
        'nombre_rol',
        'descripcion',
    ];

    public $timestamps = true;


    public function users()
    {

        return $this->belongsToMany(User::class, 'user_roles', 'rol_id', 'user_id')
                    ->withPivot('fecha_asignacion') // Si quieres acceder a la columna 'fecha_asignacion' en la tabla pivote
                    ->withTimestamps(); // Si la tabla pivote 'user_roles' también tiene created_at y updated_at
    }
}