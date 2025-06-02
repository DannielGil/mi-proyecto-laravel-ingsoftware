<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function create()
    {
        return view('crearusuarios'); // O la ruta correcta de tu vista
    }

    public function store(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'activo' => 'boolean',
            'departamento_id' => 'nullable|exists:departamentos,id',
        ]);

        // Crear el usuario
        $user = new User();
        $user->nombre = $request->nombre;
        $user->apellido = $request->apellido;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->activo = $request->activo;
        $user->departamento_id = $request->departamento_id;
        $user->save();

        // Asignar roles si es necesario
        if ($request->roles) {
            foreach ($request->roles as $role) {
                $user->roles()->attach($role);
            }
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }
}