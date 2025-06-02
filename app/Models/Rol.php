<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    // Asegúrate de que esta línea esté presente y apunte a 'roles'
    protected $table = 'roles';

    // Si tu clave primaria no es 'id', especifícala (ej. 'rol_id')
    protected $primaryKey = 'rol_id';

    // Los atributos que se pueden asignar masivamente
    protected $fillable = [
        'nombre_rol',
        'descripcion',
    ];

    // Deshabilita los timestamps si tu tabla no tiene 'created_at' y 'updated_at'
    // public $timestamps = false;

    /**
     * Relación con Usuarios (Muchos a Muchos)
     */
    public function usuarios()
    {
        // El primer argumento es el modelo relacionado (Usuario)
        // El segundo argumento es el nombre de la tabla pivote (usuario_roles)
        // El tercer argumento es la clave foránea del modelo actual (Rol) en la tabla pivote (rol_id)
        // El cuarto argumento es la clave foránea del modelo relacionado (Usuario) en la tabla pivote (usuario_id)
        return $this->belongsToMany(Usuario::class, 'usuario_roles', 'rol_id', 'usuario_id')
                    ->withPivot('fecha_asignacion'); // Si tienes esta columna en la tabla pivote
    }
}