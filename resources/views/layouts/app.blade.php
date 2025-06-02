<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Shirotech</title>

    {{-- Estilos de Bootstrap (si usas CDN) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjLqS5FVxGz5pHsA9QJjSm8VfK3N/31lG9/J90JjB/N" crossorigin="anonymous">

    {{-- Estilos generales del dashboard --}}
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

    {{-- Iconos de Bootstrap --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Estilos específicos por rol --}}
    @auth
        {{-- Aquí se usarán los métodos isCoordinador(), isDocente(), etc. --}}
        {{-- Asegúrate de que estos archivos CSS existen en tu carpeta public/css --}}
        @if(Auth::user()->isCoordinador())
            <link href="{{ asset('css/coordinador.css') }}" rel="stylesheet">
        @elseif(Auth::user()->isDocente())
            <link href="{{ asset('css/docente.css') }}" rel="stylesheet">
        @elseif(Auth::user()->isAdministrador())
            <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
        @elseif(Auth::user()->isRector())
            <link href="{{ asset('css/rector.css') }}" rel="stylesheet">
        {{-- Si tienes otros roles como Supervisor o Director con CSS específico, añádelos aquí --}}
        @endif
    @endauth
</head>
<body>
    <div id="app">
        <div class="dashboard-container">
            <div class="sidebar">
                <div class="sidebar-header">
                    <h3>Shirotech</h3>
                </div>
                <ul class="sidebar-menu">
                    {{-- Siempre mostrar el enlace al dashboard general si existe --}}
                    <li>
                        <a href="{{ route('dashboard') }}"> {{-- Asegúrate de que esta ruta 'dashboard' existe y redirige según el rol --}}
                            <i class="bi bi-house-door-fill"></i> Navegación                        </a>
                    </li>

                    @auth
                        {{-- --- Enlaces específicos para ADMINISTRADOR --- --}}
                        @if(Auth::user()->isAdministrador())
                            <li>
                                <a href="{{ route('usuarios.create') }}">
                                    <i class="bi bi-person-plus-fill"></i> Crear Usuario
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('usuarios.index') }}">
                                    <i class="bi bi-people-fill"></i> Ver Usuarios
                                </a>
                            </li>
                        @endif

                        {{-- --- Enlaces específicos para DOCENTE --- --}}
                        @if(Auth::user()->isDocente())
                            <li>
                                <a href="{{ route('proyectos.mis_proyectos') }}">
                                    <i class="bi bi-folder-fill"></i> Mis Proyectos
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('proyectos.create') }}">
                                    <i class="bi bi-plus-circle-fill"></i> Crear Nuevo Proyecto
                                </a>
                            </li>
                        @endif

                        {{-- --- Enlaces específicos para COORDINADOR --- --}}
                        @if(Auth::user()->isCoordinador())
                            <li>
                                <a href="{{ route('proyectos.index') }}"> {{-- Podría ser un índice general de proyectos para supervisar --}}
                                    <i class="bi bi-journal-check"></i> Proyectos
                                </a>
                            </li>
                        @endif

                        {{-- --- Enlaces específicos para RECTOR --- --}}
                        @if(Auth::user()->isRector())
                            <li>
                                <a href="{{ route('metricas.proyectos') }}">
                                    <i class="bi bi-bar-chart-fill"></i> Métricas de Proyectos
                                </a>
                            </li>
                        @endif


                    @endauth
                </ul>
            </div>

            <div class="main-content">
                <div class="navbar">
                    <div class="navbar-left">
                        <h2>@yield('page_title', 'Página principal')</h2>
                    </div>
                    <ul class="navbar-nav ms-auto">
                        @guest
                            {{-- Si el usuario no está autenticado, muestra el enlace de Login --}}
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                        @else
                            {{-- Si el usuario está autenticado, muestra su nombre y el menú desplegable --}}
                            <li class="nav-item dropdown">
                                <a id="navbarDropdownUser" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->nombre }} {{ Auth::user()->apellido }} {{-- Muestra el nombre y apellido del usuario --}}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">

                                    {{-- Botón de Cerrar Sesión para TODOS los usuarios autenticados --}}
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Cerrar Sesión') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>

                @yield('content')
            </div>
        </div>
    </div>

    {{-- Scripts de Bootstrap (al final del body para un mejor rendimiento) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>