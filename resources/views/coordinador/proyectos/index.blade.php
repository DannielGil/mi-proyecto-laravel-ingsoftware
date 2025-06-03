{{-- resources/views/coordinador/proyectos/index.blade.php --}}

@extends('layouts.app')

@section('page_title', 'Proyectos de Docentes - Coordinador')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Supervisión de Proyectos de Docentes</h1>
            {{-- Botón "Volver al inicio" --}}
            <a href="{{ route('coordinador.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver al inicio
            </a>
        </div>

        @if($proyectos->isEmpty())
            <div class="alert alert-info" role="alert">
                No hay proyectos disponibles para supervisar en este momento.
            </div>
        @else
            @php
                // Agrupar los proyectos por el nombre completo del docente
                // Usamos optional() para evitar el error si $proyecto->docente es null
                $proyectosAgrupadosPorDocente = $proyectos->groupBy(function($proyecto) {
                    // Asegúrate de que esta relación 'creador' es la que usas en tu modelo Proyecto
                    return optional($proyecto->creador)->nombre . ' ' . optional($proyecto->creador)->apellido;
                });
            @endphp

            @foreach($proyectosAgrupadosPorDocente as $nombreCompletoDocente => $proyectosDelDocente)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Docente: {{ $nombreCompletoDocente }}</h4>
                        <small>({{ $proyectosDelDocente->count() }} proyecto{{ $proyectosDelDocente->count() == 1 ? '' : 's' }})</small>
                    </div>
                    <div class="card-body">
                        @foreach($proyectosDelDocente as $proyecto)
                            <div class="card mb-3 border-secondary">
                                <div class="card-header bg-light text-dark d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Proyecto: {{ $proyecto->titulo }} (ID: {{ $proyecto->proyecto_id }})</h5>
                                    <span class="badge {{
                                        $proyecto->estado == 'Aprobado' ? 'bg-success' :
                                        ($proyecto->estado == 'Rechazado' ? 'bg-danger' :
                                        ($proyecto->estado == 'Pendiente' ? 'bg-warning text-dark' :
                                        'bg-secondary'))
                                    }}">
                                        {{ $proyecto->estado }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-3">Descripción:</dt>
                                        <dd class="col-sm-9">
                                            {{ Str::limit($proyecto->descripcion, 200) }}
                                            @if(strlen($proyecto->descripcion) > 200)
                                                <a href="{{ route('proyectos.show', $proyecto->proyecto_id) }}" class="text-info small" title="Ver Descripción Completa">Leer más</a>
                                            @endif
                                        </dd>

                                        <dt class="col-sm-3">Fecha Inicio:</dt>
                                        <dd class="col-sm-9">{{ $proyecto->fecha_inicio }}</dd>

                                        <dt class="col-sm-3">Fecha Fin Estimada:</dt>
                                        <dd class="col-sm-9">{{ $proyecto->fecha_fin_estimada }}</dd>

                                        <dt class="col-sm-3">Presupuesto ($):</dt>
                                        <dd class="col-sm-9">${{ number_format($proyecto->presupuesto, 2, ',', '.') }}</dd>

                                        <dt class="col-sm-3">Departamento:</dt>
                                        <dd class="col-sm-9">{{ optional($proyecto->departamento)->nombre ?? 'N/A' }}</dd>
                                    </dl>
                                    <hr class="my-3">
                                    <div class="d-flex justify-content-end">
                                        @if($proyecto->estado == 'Pendiente')
                                            <form action="{{ route('proyectos.aprobar', $proyecto->proyecto_id) }}" method="POST" class="d-inline me-2">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-success" title="Aprobar Proyecto">
                                                    <i class="bi bi-check-circle"></i> Aprobar
                                                </button>
                                            </form>
                                            <form action="{{ route('proyectos.rechazar', $proyecto->proyecto_id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Rechazar Proyecto">
                                                    <i class="bi bi-x-circle"></i> Rechazar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            ---

            {{-- Links de paginación --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $proyectos->links() }}
            </div>
        @endif
    </div>
@endsection