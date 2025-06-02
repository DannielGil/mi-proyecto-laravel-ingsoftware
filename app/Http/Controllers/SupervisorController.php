<?php

namespace App\Http\Controllers; // <--- ¡Asegúrate de que este namespace sea correcto!

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Asegúrate de que el controlador base esté importado

class SupervisorController extends Controller
{
    /**
     * Muestra el dashboard del Supervisor.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Aquí puedes cargar datos específicos para el dashboard del Supervisor
        // Por ejemplo: $proyectosAsignados = Proyecto::where('supervisor_id', Auth::id())->get();

        return view('supervisor.dashboard'); // Asegúrate de crear esta vista
    }
}