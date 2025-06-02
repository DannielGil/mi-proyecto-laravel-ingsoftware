<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Asegúrate de importar DB

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insertar los roles en la tabla 'roles'
        DB::table('roles')->insert([
            ['nombre_rol' => 'Administrador', 'descripcion' => 'Rol con acceso total al sistema.', 'created_at' => now(), 'updated_at' => now()],
            ['nombre_rol' => 'Docente', 'descripcion' => 'Rol para profesores y personal académico.', 'created_at' => now(), 'updated_at' => now()],
            ['nombre_rol' => 'Coordinador', 'descripcion' => 'Rol para coordinadores de áreas o programas.', 'created_at' => now(), 'updated_at' => now()],
            ['nombre_rol' => 'Rector', 'descripcion' => 'Rol para el rector o director de la institución.', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}