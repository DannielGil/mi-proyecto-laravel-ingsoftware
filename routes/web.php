<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- Importar Controladores ---
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UsuarioManagementController;
use App\Http\Controllers\ProyectoController; // Asegúrate de tener este controlador

// Controladores para Dashboards específicos por rol
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\CoordinadorController;
use App\Http\Controllers\ProfesorController; // Cambiado a ProfesorController, si es el controlador de docentes
use App\Http\Controllers\RectorController; // <-- ¡NUEVO! Importa el controlador del Rector
use App\Http\Controllers\SupervisorController; // Si el Supervisor es un rol distinto y no es Coordinador

// --- 1. Ruta Raíz (/) ---
// Siempre redirige a login, sin comprobar si está autenticado.
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// --- 2. Rutas de Autenticación Personalizadas ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    // Rutas de Recuperación de Contraseña
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

// --- 3. Rutas de Usuarios Autenticados (Middleware 'auth') ---
// Todas las rutas dentro de este grupo requieren que el usuario esté logueado.
Route::middleware(['auth'])->group(function () {

    // Ruta de Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Dashboard Principal (Redirección por rol)
    // Este `dashboard` puede ser genérico y redirigir según el rol del usuario logueado.
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Rutas de Dashboards Específicos por Rol y su Funcionalidad ---

    // Rol: ADMINISTRADOR
    Route::middleware(['role:Administrador'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        // Gestión de Usuarios (Crear y Ver usuario)
        Route::prefix('usuarios')->name('usuarios.')->group(function () {
            Route::get('/', [UsuarioManagementController::class, 'index'])->name('index'); // Ver usuarios
            Route::get('/crear', [UsuarioManagementController::class, 'create'])->name('create'); // Crear usuario
            Route::post('/', [UsuarioManagementController::class, 'store'])->name('store');
            Route::get('/{usuario}/editar', [UsuarioManagementController::class, 'edit'])->name('edit');
            Route::put('/{usuario}', [UsuarioManagementController::class, 'update'])->name('update');
            Route::delete('/{usuario}', [UsuarioManagementController::class, 'destroy'])->name('destroy');
        });
    });

    // Rol: DOCENTE
    Route::middleware(['role:Docente'])->group(function () {
        Route::get('/docente/dashboard', [ProfesorController::class, 'index'])->name('docente.dashboard');

        // Rutas de Proyectos para el rol Docente
        Route::prefix('proyectos')->name('proyectos.')->group(function () {
            Route::get('/mis-proyectos', [ProyectoController::class, 'misProyectos'])->name('mis_proyectos'); // "Mis Proyectos"
            Route::get('/crear', [ProyectoController::class, 'create'])->name('create'); // "Crear Nuevo Proyecto"
            Route::post('/', [ProyectoController::class, 'store'])->name('store');
            // Rutas adicionales para registrar avances y subir documentos para los proyectos de un docente
            Route::get('/{proyecto}/editar', [ProyectoController::class, 'edit'])->name('edit'); // Para editar proyectos existentes
            Route::put('/{proyecto}', [ProyectoController::class, 'update'])->name('update'); // Para guardar avances y documentos
            Route::post('/{proyecto}/subir-documento', [ProyectoController::class, 'subirDocumento'])->name('subir_documento'); // Para subir docs a un proyecto específico
        });
    });

    // Rol: COORDINADOR
    Route::middleware(['role:Coordinador'])->group(function () {
        Route::get('/coordinador/dashboard', [CoordinadorController::class, 'index'])->name('coordinador.dashboard');

        // Rutas de Proyectos para el Coordinador (Supervisión)
        Route::prefix('proyectos')->name('proyectos.')->group(function () {
            Route::get('/', [ProyectoController::class, 'indexCoordinador'])->name('index'); // "Proyectos" (general para Coordinador)
            Route::get('/validar', [ProyectoController::class, 'validarProyectos'])->name('validar_proyectos');
            Route::get('/terminados', [ProyectoController::class, 'terminados'])->name('terminados');
            Route::get('/en_curso', [ProyectoController::class, 'en_curso'])->name('en_curso'); // Añadido nombre a la ruta
            Route::get('/{proyecto}', [ProyectoController::class, 'show'])->name('show'); // Ver detalles de un proyecto
            // Si el coordinador puede modificar el estado o detalles de un proyecto:
            Route::put('/{proyecto}/aprobar', [ProyectoController::class, 'aprobar'])->name('aprobar');
            Route::put('/{proyecto}/rechazar', [ProyectoController::class, 'rechazar'])->name('rechazar');
            // ... otras rutas de supervisión
        });
    });

    // Rol: RECTOR
    Route::middleware(['role:Rector'])->group(function () {
        Route::get('/rector/dashboard', [RectorController::class, 'index'])->name('rector.dashboard');
        // "Métricas de Proyectos"
        Route::get('/metricas/proyectos', [RectorController::class, 'metricasProyectos'])->name('metricas.proyectos');
        // Rutas adicionales para métricas específicas si las hay
    });

    // Rol: DIRECTOR (se mantiene como ejemplo, si no es Rector)
    Route::middleware(['role:Director'])->group(function () {
        Route::get('/director/dashboard', [DirectorController::class, 'index'])->name('director.dashboard');
        // ... (añade rutas específicas para Director si este rol es distinto del Rector)
    });

    // Rol: SUPERVISOR (se mantiene como ejemplo, si no es Coordinador o Rector)
    Route::middleware(['role:Supervisor'])->group(function () {
        Route::get('/supervisor/dashboard', [SupervisorController::class, 'index'])->name('supervisor.dashboard');
        // ... (añade rutas específicas para Supervisor si este rol es distinto)
    });

    // --- Rutas Comunes para Todos los Autenticados ---
    // Perfil de Usuario
    Route::get('/profile', [UsuarioManagementController::class, 'showProfile'])->name('profile.show'); // <-- Asumiendo que hay un método showProfile
    // Si tienes un controlador específico para el perfil, impórtalo y úsalo aquí.
    // Ej: use App\Http\Controllers\ProfileController;
    // Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

    // Ruta de descarga de adjuntos (puede ser accesible por varios roles)
    Route::get('/proyectos/adjunto/{adjunto}/descargar', [ProyectoController::class, 'descargarAdjunto'])->name('proyectos.descargar_adjunto');

});

### **Ruta para Acceso No Autorizado**
Route::get('/unauthorized', function () {
    return view('errors.unauthorized'); // Asegúrate de crear este archivo: resources/views/errors/unauthorized.blade.php
})->name('unauthorized');