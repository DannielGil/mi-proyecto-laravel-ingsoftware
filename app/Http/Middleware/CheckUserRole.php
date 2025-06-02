<?php

namespace App\Http\Middleware; // Asegúrate de que este namespace es correcto

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Maneja una solicitud entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  array<string>  ...$roles // <--- ¡AÑADIDO ESTO! Esto captura los argumentos pasados al middleware
     */
    public function handle(Request $request, Closure $next, ...$roles): Response // <--- ¡AÑADIDO ...$roles AQUÍ!
    {
        // 1. Verificar si el usuario está autenticado.
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user(); // Obtiene el modelo App\Models\Usuario (asumo que es tu modelo)

        // 2. Verificar si el usuario tiene ALGUNO de los roles requeridos.
        // El método hasAnyRole debe estar definido en tu modelo App\Models\Usuario.
        if (!$user->hasAnyRole($roles)) {
            // Si el usuario no tiene ninguno de los roles necesarios, redirigir a una página de acceso denegado.
            return redirect()->route('unauthorized'); // Asegúrate de que esta ruta existe en web.php
        }

        // Si el usuario tiene al menos uno de los roles requeridos, permite que la solicitud continúe
        return $next($request);
    }
}