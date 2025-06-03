<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{-- Agrega los enlaces de Bootstrap si usas clases como 'btn', 'table', 'form-control', etc. --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3>Shirotech</h3>
            </div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Inicio</a></li> {{-- Enlace al dashboard principal --}}
                <li><a href="{{ route('usuarios.index') }}"><i class="fas fa-users"></i> Gestionar usuarios</a></li>
                <li><a href="{{ route('usuarios.create') }}"><i class="fas fa-user-plus"></i> Crear usuarios</a></li>
                {{-- Bloque de cierre de sesión funcional --}}
                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </aside>

        <main class="main-content">
            <header class="navbar">
                <div class="navbar-left">
                    {{-- Muestra el nombre del usuario si está autenticado --}}
                    <h2>Bienvenido, {{ Auth::user()->nombre ?? 'Usuario' }}</h2>
                </div>
            </header>

            <section class="welcome-section">
                <p>Usa el menú de la izquierda para navegar por las opciones de administración de usuarios.</p>
            </section>

            {{-- Aquí puedes incluir el contenido específico del dashboard, o quitar esto y solo usar el layout --}}

        </main>
    </div>

    {{-- Script de Bootstrap si lo necesitas para los componentes --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>