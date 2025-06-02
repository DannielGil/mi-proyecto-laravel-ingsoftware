<?php

// database/migrations/YYYY_MM_DD_XXXXXX_add_fecha_actualizacion_to_usuarios_table.php
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
        Schema::table('usuarios', function (Blueprint $table) {
            // Añade la columna 'fecha_actualizacion', que puede ser nula al principio
            $table->timestamp('fecha_actualizacion')->nullable()->after('fecha_creacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('fecha_actualizacion');
        });
    }
};