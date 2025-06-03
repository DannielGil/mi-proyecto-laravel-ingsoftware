{{-- resources/views/proyectos/show.blade.php --}}

@extends('layouts.app') {{-- Asegúrate de que este sea el layout principal de tu aplicación --}}

@section('page_title', 'Detalles del Proyecto: ' . $proyecto->titulo)

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">Detalles del Proyecto</h1>
            <div>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
                {{-- Botón para editar si el usuario es el creador o un administrador --}}
                @if(Auth::check() && (Auth::id() === $proyecto->creador_id || Auth::user()->hasRole('Administrador')))
                    <a href="{{ route('proyectos.edit', $proyecto->proyecto_id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil-square"></i> Editar Proyecto
                    </a>
                @endif
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">{{ $proyecto->titulo }}</h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>ID del Proyecto:</strong> {{ $proyecto->proyecto_id }}</p>
                        <p><strong>Estado:</strong> 
                            <span class="badge {{
                                $proyecto->estado == 'Aprobado' ? 'bg-success' :
                                ($proyecto->estado == 'Rechazado' ? 'bg-danger' :
                                ($proyecto->estado == 'Pendiente' ? 'bg-warning text-dark' :
                                'bg-secondary'))
                            }}">
                                {{ $proyecto->estado }}
                            </span>
                        </p>
                        <p><strong>Fecha de Inicio:</strong> {{ \Carbon\Carbon::parse($proyecto->fecha_inicio)->format('d/m/Y') }}</p>
                        <p><strong>Fecha Fin Estimada:</strong> {{ \Carbon\Carbon::parse($proyecto->fecha_fin_estimada)->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Presupuesto:</strong> ${{ number_format($proyecto->presupuesto, 2, ',', '.') }}</p>
                        <p><strong>Docente Creador:</strong> 
                            @if ($proyecto->creador)
                                {{ $proyecto->creador->nombre }} {{ $proyecto->creador->apellido }}
                            @else
                                <span class="text-muted">Desconocido</span>
                            @endif
                        </p>
                        <p><strong>Departamento:</strong>
                            @if ($proyecto->departamento)
                                {{ $proyecto->departamento->nombre }}
                            @else
                                <span class="text-muted">No Asignado</span>
                            @endif
                        </p>
                        <p><strong>Fecha de Creación:</strong> {{ \Carbon\Carbon::parse($proyecto->fecha_creacion)->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <h4 class="mt-4 mb-3">Descripción</h4>
                <p class="card-text">{{ $proyecto->descripcion }}</p>

                @if($proyecto->objetivos)
                    <h4 class="mt-4 mb-3">Objetivos</h4>
                    <p class="card-text">{{ $proyecto->objetivos }}</p>
                @endif

                @if($proyecto->metodologia)
                    <h4 class="mt-4 mb-3">Metodología</h4>
                    <p class="card-text">{{ $proyecto->metodologia }}</p>
                @endif

                <h4 class="mt-4 mb-3">Archivos Adjuntos</h4>
                @if ($proyecto->adjuntos->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($proyecto->adjuntos as $adjunto)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="bi bi-file-earmark me-2"></i>
                                    {{ $adjunto->nombre_original }} 
                                    <small class="text-muted">({{ strtoupper($adjunto->extension) }})</small>
                                </span>
                                <a href="{{ route('proyectos.descargar_adjunto', $adjunto->id) }}" class="btn btn-sm btn-info" title="Descargar Adjunto">
                                    <i class="bi bi-download"></i> Descargar
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No hay archivos adjuntos para este proyecto.</p>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    {{-- Asegúrate de tener los iconos de Bootstrap cargados en tu layout principal o aquí --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush