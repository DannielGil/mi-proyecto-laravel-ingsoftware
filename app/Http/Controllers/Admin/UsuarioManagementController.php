<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Departamento;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; // <-- ¡Esta es la línea que faltaba!

class UsuarioManagementController extends Controller
{
    /**
     * Muestra una lista de usuarios.
     */
    public function index()
    {
        $usuarios = Usuario::with('departamento', 'roles')->paginate(10);
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        $departamentos = Departamento::all();
        $roles = Rol::all();
        return view('usuarios.create', compact('departamentos', 'roles'));
    }

    /**
     * Almacena un nuevo usuario en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:usuarios,email',
            'password' => 'required|string|min:8|confirmed',
            'departamento_id' => 'nullable|exists:departamentos,departamento_id',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,rol_id',
            'activo' => 'boolean',
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'departamento_id' => $request->departamento_id,
            'activo' => $request->has('activo'),
        ]);

        if ($request->has('roles')) {
            $usuario->roles()->sync($request->roles);
        } else {
            $usuario->roles()->detach();
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Muestra los detalles de un usuario específico (opcional, para ver un perfil).
     */
    public function show(Usuario $usuario)
    {
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Muestra el formulario para editar un usuario existente.
     */
    public function edit(Usuario $usuario)
    {
        $departamentos = Departamento::all();
        $roles = Rol::all();
        $usuarioRoles = $usuario->roles->pluck('rol_id')->toArray();

        return view('usuarios.edit', compact('usuario', 'departamentos', 'roles', 'usuarioRoles'));
    }

    /**
     * Actualiza un usuario en la base de datos.
     */
    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('usuarios', 'email')->ignore($usuario->usuario_id, 'usuario_id'),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'departamento_id' => 'nullable|exists:departamentos,departamento_id',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,rol_id',
            'activo' => 'boolean',
        ]);

        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->email = $request->email;
        $usuario->departamento_id = $request->departamento_id;
        $usuario->activo = $request->has('activo');

        if ($request->filled('password')) {
            $usuario->password_hash = Hash::make($request->password);
        }

        $usuario->save();

        if ($request->has('roles')) {
            $usuario->roles()->sync($request->roles);
        } else {
            $usuario->roles()->detach();
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Elimina un usuario de la base de datos.
     */
    public function destroy(Usuario $usuario)
    {
        // Opcional: prevenir eliminar el propio usuario autenticado o un administrador
        if (Auth::id() === $usuario->usuario_id) { // <-- ¡Ahora Auth::id() funcionará!
            return redirect()->route('usuarios.index')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $usuario->roles()->detach();
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}