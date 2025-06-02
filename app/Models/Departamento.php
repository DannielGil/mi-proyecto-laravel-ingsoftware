<?php

namespace App\Models; // <-- ¡ESTE NAMESPACE ES CRUCIAL!

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    // Si tu tabla de departamentos se llama 'departamentos' (plural)
    // Eloquent lo infiere por defecto, pero puedes especificarlo explícitamente:
    // protected $table = 'departamentos';

    // Si tu clave primaria no es 'id' (ej. 'departamento_id'), especifícala:
    protected $primaryKey = 'departamento_id';

    // Los atributos que se pueden asignar masivamente
    protected $fillable = [
        'nombre_departamento',
        'ubicacion',
        // Agrega aquí todas las columnas de tu tabla 'departamentos' que quieres poder llenar masivamente
    ];

    // Si no usas timestamps (created_at, updated_at), deshabilítalos
    // public $timestamps = false;

    /**
     * Relación con Usuarios (Uno a Muchos)
     * Un departamento tiene muchos usuarios.
     */
    public function usuarios()
    {
        // El primer argumento es el modelo relacionado (Usuario)
        // El segundo argumento es la clave foránea en la tabla de 'usuarios' que apunta a 'departamentos' (departamento_id)
        // El tercer argumento es la clave local de este modelo (Departamento) (departamento_id)
        return $this->hasMany(Usuario::class, 'departamento_id', 'departamento_id');
    }
}