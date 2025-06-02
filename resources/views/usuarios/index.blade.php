@extends('layouts.app') {{-- O el layout base que uses para tu dashboard --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Gestionar Usuarios') }}</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">Crear Nuevo Usuario</a>
                        {{-- Botón "Volver al Dashboard" --}}
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary ms-2">Volver al Dashboard</a>
                    </div>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Email</th>
                                <th>Departamento</th>
                                <th>Roles</th>
                                <th>Activo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($usuarios as $usuario)
                                <tr>
                                    <td>{{ $usuario->usuario_id }}</td>
                                    <td>{{ $usuario->nombre }}</td>
                                    <td>{{ $usuario->apellido }}</td>
                                    <td>{{ $usuario->email }}</td>
                                    <td>{{ $usuario->departamento->nombre_departamento ?? 'N/A' }}</td>
                                    <td>
                                        @forelse ($usuario->roles as $rol)
                                            <span class="badge bg-info">{{ $rol->nombre_rol }}</span>
                                        @empty
                                            Sin roles
                                        @endforelse
                                    </td>
                                    <td>
                                        @if ($usuario->activo)
                                            <span class="badge bg-success">Sí</span>
                                        @else
                                            <span class="badge bg-danger">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('usuarios.edit', $usuario->usuario_id) }}" class="btn btn-sm btn-warning">Editar</a>
                                        <form action="{{ route('usuarios.destroy', $usuario->usuario_id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar a este usuario?');">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">No hay usuarios registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $usuarios->links() }} {{-- Para mostrar los enlaces de paginación --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection