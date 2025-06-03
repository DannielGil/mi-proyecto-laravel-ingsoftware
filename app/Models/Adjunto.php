<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adjunto extends Model
{
    use HasFactory;

    // Define el nombre de la tabla si no sigue la convención de Laravel (plural del nombre del modelo)
    // protected $table = 'adjuntos'; // En este caso, 'adjuntos' es el nombre por defecto, así que es opcional

    // Define los campos que se pueden asignar masivamente (fillable)
    protected $fillable = [
        'proyecto_id',          // Clave foránea para relacionar con un proyecto
        'nombre_original',      // Nombre original del archivo cuando fue subido
        'ruta_almacenamiento',  // Ruta donde el archivo está guardado en el sistema de archivos (ej. 'public/uploads/proyectos/nombre_hash.pdf')
        'mime_type',            // Tipo MIME del archivo (ej. 'application/pdf', 'image/jpeg')
        'tamano',               // Tamaño del archivo en bytes
    ];

    /**
     * Relación: Un adjunto pertenece a un proyecto.
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id', 'proyecto_id');
    }
}