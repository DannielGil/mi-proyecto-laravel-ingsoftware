<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    // La clave primaria de tu tabla es 'proyecto_id'.
    protected $primaryKey = 'proyecto_id';

    // Tu tabla no usa las columnas 'created_at' y 'updated_at' de Laravel por defecto.
    // Usas 'fecha_creacion' manualmente.
    public $timestamps = false; // Correcto, ya que manejas tus propias marcas de tiempo

    // Nombre de la tabla si no sigue la convención de nombres de Laravel (plural del nombre del modelo)
    // protected $table = 'proyectos'; // Descomenta si tu tabla no se llama 'proyectos'

    // Campos que pueden ser asignados masivamente (Mass Assignment)
    protected $fillable = [
        'titulo',
        'descripcion',
        'objetivos',    // Incluidos aquí, como ya los habías puesto en el controlador
        'metodologia',  // Incluidos aquí, como ya los habías puesto en el controlador
        'fecha_inicio',
        'fecha_fin_estimada',
        'presupuesto',
        'estado',
        'departamento_id',
        'creador_id', // Importante: Campo que referencia al usuario (docente) creador
        'fecha_creacion', // También un campo que puede ser asignado masivamente si lo manejas
    ];

    // Para que Laravel trate estas columnas como objetos Carbon (fechas)
    protected $casts = [ // Preferible usar 'casts' para fechas en lugar de 'dates' en Laravel 7+
        'fecha_inicio' => 'date',
        'fecha_fin_estimada' => 'date',
        'fecha_creacion' => 'datetime', // 'datetime' es más común para campos de creación
        'presupuesto' => 'float', // Podrías castear el presupuesto a float también para asegurar tipo
    ];


    /**
     * Relación: Un proyecto pertenece a un Departamento.
     * 'departamento_id' en 'proyectos' es la clave foránea.
     * 'departamento_id' es la clave primaria en la tabla 'departamentos'.
     */
    public function departamento()
    {
        // Asegúrate de que el modelo Departamento tenga 'departamento_id' como primaryKey si no es 'id'
        // y que su tabla se llame 'departamentos'
        return $this->belongsTo(Departamento::class, 'departamento_id', 'departamento_id');
    }

    /**
     * Relación: Un proyecto tiene muchos Adjuntos.
     * 'proyecto_id' en 'adjuntos' es la clave foránea que referencia a 'proyecto_id' de esta tabla.
     */
    public function adjuntos()
    {
        // Asegúrate de que el modelo Adjunto tenga 'proyecto_id' como clave foránea
        // y que su tabla se llame 'adjuntos'
        return $this->hasMany(Adjunto::class, 'proyecto_id', 'proyecto_id');
    }

    /**
     * Relación: Un proyecto pertenece a un Usuario (el docente creador).
     * 'creador_id' en 'proyectos' es la clave foránea.
     * 'usuario_id' es la clave primaria en la tabla 'usuarios'.
     *
     * Importante: Asegúrate de que este modelo 'Usuario' exista y sea el correcto.
     * Si tu modelo de usuario se llama 'User' (por defecto de Laravel), cámbialo a `User::class`.
     */
    public function creador()
    {
        // Si tu modelo de usuario se llama 'User' (el predeterminado de Laravel),
        // deberías usar 'App\Models\User' en lugar de 'App\Models\Usuario'.
        // Si lo renombraste, entonces 'Usuario::class' es correcto.
        return $this->belongsTo(Usuario::class, 'creador_id', 'usuario_id');
    }
}