<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Usuario; // Asegúrate de que este sea el modelo de usuario correcto, usualmente es 'User'
use App\Models\Departamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RectorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Rector']);
    }

    public function index()
    {
        return view('rector');
    }

    public function metricasProyectos()
    {
        // --- Métricas existentes ---
        $totalProyectos = Proyecto::count();

        $proyectosPorEstado = Proyecto::select('estado', DB::raw('count(*) as total'))
                                      ->groupBy('estado')
                                      ->get();

        $proyectosPorDepartamento = Proyecto::with('departamento')
                                            ->select('departamento_id', DB::raw('count(*) as total'))
                                            ->groupBy('departamento_id')
                                            ->get()
                                            ->map(function ($item) {
                                                // Asegúrate de que la relación 'departamento' exista y sea correcta
                                                $item->nombre_departamento = $item->departamento ? $item->departamento->nombre : 'Sin Departamento';
                                                return $item;
                                            });

        // Asegúrate de que el modelo Proyecto tenga una relación 'creador' que apunte a 'Usuario' (o 'User')
        // Ejemplo: public function creador() { return $this->belongsTo(Usuario::class, 'user_id'); }
        $topCreadores = Proyecto::with('creador')
                                ->select('creador_id', DB::raw('count(*) as total'))
                                ->groupBy('creador_id')
                                ->orderBy('total', 'desc')
                                ->take(5)
                                ->get()
                                ->map(function ($item) {
                                    // Asume que tu modelo Usuario tiene campos 'nombre' y 'apellido'
                                    $item->nombre_creador = $item->creador ? $item->creador->nombre . ' ' . $item->creador->apellido : 'Desconocido';
                                    return $item;
                                });

        $presupuestoTotalAcumulado = Proyecto::sum('presupuesto');

        // Para la tabla paginada de proyectos con presupuesto (si aún la quieres)
        $proyectosConPresupuesto = Proyecto::where('presupuesto', '>', 0) // Solo proyectos con presupuesto > 0
                                            ->orderBy('presupuesto', 'desc')
                                            ->paginate(10);


        // --- Datos para Gráficas (Chart.js) ---

        // Gráfica de Proyectos por Estado (Torta/Dona)
        $chartEstadosLabels = $proyectosPorEstado->pluck('estado')->toArray();
        $chartEstadosData = $proyectosPorEstado->pluck('total')->toArray();

        // Gráfica de Proyectos por Departamento (Barras)
        $chartDepartamentosLabels = $proyectosPorDepartamento->pluck('nombre_departamento')->toArray();
        $chartDepartamentosData = $proyectosPorDepartamento->pluck('total')->toArray();

        // Gráfica de Top Creadores (Barras)
        $chartCreadoresLabels = $topCreadores->pluck('nombre_creador')->toArray();
        $chartCreadoresData = $topCreadores->pluck('total')->toArray();

        // --- Gráfica de Presupuesto Detallado por Proyecto (Torta/Dona) ---
        // Para esta gráfica, necesitas una colección de proyectos, no solo la suma.
        // Filtramos solo los proyectos con presupuesto para la gráfica, ordenados por presupuesto.
        $proyectosParaGraficaPresupuesto = Proyecto::where('presupuesto', '>', 0)
                                                    ->orderBy('presupuesto', 'desc')
                                                    ->get(); // Obtener la colección completa para pluck

        $chartPresupuestoLabels = $proyectosParaGraficaPresupuesto->pluck('titulo')->toArray();
        $chartPresupuestoData = $proyectosParaGraficaPresupuesto->pluck('presupuesto')->toArray();


        // Retorna la vista y pasa todas las métricas y datos para gráficas
        return view('rector.metricas_proyectos', compact(
            'totalProyectos',
            'proyectosPorEstado',
            'proyectosPorDepartamento',
            'topCreadores',
            'presupuestoTotalAcumulado', // Este es un valor numérico, no una colección
            'proyectosConPresupuesto', // Para la tabla paginada

            'chartEstadosLabels',
            'chartEstadosData',
            'chartDepartamentosLabels',
            'chartDepartamentosData',
            'chartCreadoresLabels',
            'chartCreadoresData',

            // Variables para la gráfica de presupuesto por proyecto
            'chartPresupuestoLabels',
            'chartPresupuestoData'
        ));
    }
}