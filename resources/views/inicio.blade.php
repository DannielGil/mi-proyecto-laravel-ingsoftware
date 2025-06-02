{{-- En resources/views/inicio.blade.php o en tu barra de navegación --}}

@auth {{-- Muestra el enlace solo si el usuario está autenticado --}}
    @if (Auth::user()->hasRole('Administrador')) {{-- Usamos el método hasRole que definimos en el modelo User --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.users.index') }}">Gestión de Usuarios</a>
        </li>
    @endif
@endauth