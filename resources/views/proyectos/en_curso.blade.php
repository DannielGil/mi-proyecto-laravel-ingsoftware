@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Proyectos en Curso') }}</div>

                <div class="card-body">
                    @if ($proyectosEnCurso->isEmpty())
                        <div class="alert alert-info" role="alert">
                            No hay proyectos con estado "en curso" en este momento.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Descripción</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin Estimada</th>
                                        <th>Presupuesto</th>
                                        <th>Estado</th>
                                        <th>Departamento</th>
                                        <th>Fecha Creación</th>
                                        <th>Adjuntos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($proyectosEnCurso as $proyecto)
                                        <tr>
                                            <td><strong>{{ $proyecto->titulo }}</strong></td>
                                            <td>{{ Str::limit($proyecto->descripcion, 50) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($proyecto->fecha_inicio)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($proyecto->fecha_fin_estimada)->format('d/m/Y') }}</td>
                                            <td>${{ number_format($proyecto->presupuesto, 2, ',', '.') }}</td>
                                            <td><span class="badge bg-warning text-dark">{{ $proyecto->estado }}</span></td>
                                            <td>{{ $proyecto->departamento->nombre_departamento ?? 'N/A' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($proyecto->fecha_creacion)->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @if ($proyecto->adjuntos->isNotEmpty())
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-info dropdown-toggle" type="button" id="dropdownAdjuntos{{ $proyecto->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Descargar ({{ $proyecto->adjuntos->count() }})
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownAdjuntos{{ $proyecto->id }}">
                                                            @foreach ($proyecto->adjuntos as $adjunto)
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ route('proyectos.descargar_adjunto', $adjunto->id) }}">
                                                                        {{ $adjunto->nombre_original }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @else
                                                    Sin adjuntos
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $proyectosEnCurso->links() }} {{-- Para la paginación --}}
                    @endif
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('coordinador.dashboard') }}" class="btn btn-secondary">Volver al Dashboard</a>
            </div>
        </div>
    </div>
</div>
@endsection