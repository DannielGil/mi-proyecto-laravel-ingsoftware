<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- Importar Controladores ---
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UsuarioManagementController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\DepartamentoController; // Agregado para el manejo de Departamentos

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\CoordinadorController;
use App\Http\Controllers\ProfesorController; // Controlador del Docente
use App\Http\Controllers\RectorController;
use App\Http\Controllers\SupervisorController;

// --- 1. Ruta Raíz (/) ---
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// --- 2. Rutas de Autenticación Personalizadas ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

// --- 3. Rutas de Usuarios Autenticados (Middleware 'auth') ---
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ====================================================================
    // Rutas de PROYECTOS que pueden ser accesibles por MÚLTIPLES ROLES
    // (La autorización detallada para cada método está en ProyectoController::__construct)
    // ====================================================================
    Route::prefix('proyectos')->name('proyectos.')->group(function () {
        // Mover la ruta de descarga de adjuntos (más específica) ARRIBA
        Route::get('/adjunto/{adjunto}/descargar', [ProyectoController::class, 'descargarAdjunto'])->name('descargar_adjunto');

        // Luego las rutas con un comodín (menos específicas)
        Route::get('/{proyecto}', [ProyectoController::class, 'show'])->name('show');
        Route::put('/{proyecto}/aprobar', [ProyectoController::class, 'aprobar'])->name('aprobar');
        Route::put('/{proyecto}/rechazar', [ProyectoController::class, 'rechazar'])->name('rechazar');
        
    });
    // ====================================================================


    // Rol: ADMINISTRADOR
    Route::middleware(['role:Administrador'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::prefix('usuarios')->name('usuarios.')->group(function () {
            Route::get('/', [UsuarioManagementController::class, 'index'])->name('index');
            Route::get('/crear', [UsuarioManagementController::class, 'create'])->name('create');
            Route::post('/', [UsuarioManagementController::class, 'store'])->name('store');
            Route::get('/{usuario}/editar', [UsuarioManagementController::class, 'edit'])->name('edit');
            Route::put('/{usuario}', [UsuarioManagementController::class, 'update'])->name('update');
            Route::delete('/{usuario}', [UsuarioManagementController::class, 'destroy'])->name('destroy');
        });

        // Rutas de Departamentos (para el Administrador)
        Route::prefix('departamentos')->name('departamentos.')->group(function () {
            Route::get('/', [DepartamentoController::class, 'index'])->name('index'); // Esta es la ruta 'departamentos.index'
            Route::get('/crear', [DepartamentoController::class, 'create'])->name('create');
            Route::post('/', [DepartamentoController::class, 'store'])->name('store');
            Route::get('/{departamento}/editar', [DepartamentoController::class, 'edit'])->name('edit');
            Route::put('/{departamento}', [DepartamentoController::class, 'update'])->name('update');
            Route::delete('/{departamento}', [DepartamentoController::class, 'destroy'])->name('destroy');
        });
    });

    // Rol: DOCENTE
    Route::middleware(['role:Docente'])->group(function () {
        Route::get('/docente/dashboard', [ProfesorController::class, 'index'])->name('docente.dashboard');

        // Rutas de Proyectos para el rol Docente
        Route::prefix('proyectos')->name('proyectos.')->group(function () {
            Route::get('docentes/mis-proyectos', [ProyectoController::class, 'misProyectos'])->name('mis_proyectos');
            Route::get('/proyectos/create', [ProyectoController::class, 'create'])->name('create'); // Simplificado la URI
            Route::post('/', [ProyectoController::class, 'store'])->name('store');
            Route::get('/{proyecto}/editar', [ProyectoController::class, 'edit'])->name('edit');
            Route::put('/{proyecto}', [ProyectoController::class, 'update'])->name('update');
            Route::post('/{proyecto}/subir-documento', [ProyectoController::class, 'subirDocumento'])->name('subir_documento');
            Route::delete('/adjuntos/{adjunto}', [ProyectoController::class, 'eliminarAdjunto'])->name('eliminar_adjunto'); 
        });
    });

    // Rol: COORDINADOR
    Route::middleware(['role:Coordinador'])->group(function () {
        Route::get('/coordinador/dashboard', [CoordinadorController::class, 'index'])->name('coordinador.dashboard');

        // Rutas de Proyectos para el Coordinador (Supervisión)
        Route::prefix('proyectos')->name('proyectos.')->group(function () {
            Route::get('proyectos/index', [ProyectoController::class, 'indexCoordinador'])->name('indexCoordinador');
            // ¡CORREGIDO AQUÍ! Se añadió un nombre explícito a la ruta de validar proyectos
            Route::get('/validar', [ProyectoController::class, 'validarProyectos'])->name('validar_proyectos');
            Route::get('/terminados', [ProyectoController::class, 'terminados'])->name('terminados');
            Route::get('/en-curso', [ProyectoController::class, 'enCurso'])->name('en_curso'); 
        });
    });

    // Rol: RECTOR
    Route::middleware(['role:Rector'])->group(function () {
        Route::get('/rector/dashboard', [RectorController::class, 'index'])->name('rector.dashboard');
        Route::get('/metricas/proyectos', [RectorController::class, 'metricasProyectos'])->name('metricas.proyectos');
    });

    // Rol: DIRECTOR
    Route::middleware(['role:Director'])->group(function () {
        Route::get('/director/dashboard', [DirectorController::class, 'index'])->name('director.dashboard');
    });

    // Rol: SUPERVISOR
    Route::middleware(['role:Supervisor'])->group(function () {
        Route::get('/supervisor/dashboard', [SupervisorController::class, 'index'])->name('supervisor.dashboard');
    });

    // --- Rutas Comunes para Todos los Autenticados ---
    Route::get('/profile', [UsuarioManagementController::class, 'showProfile'])->name('profile.show');

}); // Fin del grupo de middleware 'auth'

// Ruta para Acceso No Autorizado (si el middleware de rol de Spatie lo usa)
Route::get('/unauthorized', function () {
    return view('errors.unauthorized');
})->name('unauthorized');