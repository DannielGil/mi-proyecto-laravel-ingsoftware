<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('adjuntos', function (Blueprint $table) {
            $table->id(); // Clave primaria autoincremental

            // Clave foránea para relacionar con la tabla 'proyectos'
            // Asegúrate de que 'id' en la tabla 'proyectos' sea un unsignedBigInteger si usas $table->id()
            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');

            $table->string('nombre_original');      // Nombre del archivo como lo subió el usuario
            $table->string('ruta_almacenamiento');  // Ruta interna donde se guarda el archivo
            $table->string('mime_type')->nullable(); // Tipo de archivo (ej. application/pdf)
            $table->unsignedBigInteger('tamano')->nullable(); // Tamaño del archivo en bytes

            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjuntos');
    }
};