<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder; // ¡Importa tu RoleSeeder!
use Database\Seeders\DepartamentoSeeder; // Si también creas un seeder para Departamentos
use Database\Seeders\AdminUserSeeder; // O cualquier otro seeder específico

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {

        $this->call([
            RoleSeeder::class, 

        ]);


        User::factory()->create([
            'nombre' => 'Administrador General',
            'apellido' => 'Del Sistema',
            'email' => 'admin@tudominio.com',
            'password' => bcrypt('password'),
            'activo' => true,
            'departamento_id' => null, 
        ]);


        $adminUser = User::where('email', 'admin@tudominio.com')->first();
        $adminRole = \App\Models\Role::where('nombre', 'Administrador')->first();
        if ($adminUser && $adminRole) {
            $adminUser->roles()->attach($adminRole->rol_id);
        }
    }
}