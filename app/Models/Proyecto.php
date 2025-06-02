<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    // Asegúrate de tener estas columnas en tu propiedad $fillable si las usas para creación/actualización masiva
    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_fin_estimada',
        'presupuesto',
        'estado',
        'departamento_id',
        'fecha_creacion', // Si 'fecha_creacion' es una columna manual y no usa timestamps()
    ];

    // Si tu clave primaria es 'proyecto_id' y no 'id'
    // protected $primaryKey = 'proyecto_id';

    // Para que Laravel trate las columnas de fecha como objetos Carbon
    protected $dates = ['fecha_inicio', 'fecha_fin_estimada', 'fecha_creacion'];


    /**
     * Relación: Un proyecto pertenece a un departamento.
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    /**
     * Relación: Un proyecto tiene muchos adjuntos.
     */
    public function adjuntos()
    {
        return $this->hasMany(Adjunto::class, 'proyecto_id'); // 'proyecto_id' es la FK en la tabla 'adjuntos'
    }
}