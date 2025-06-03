@extends('layouts.app') {{-- O el layout que uses para los dashboards --}}

@section('page_title', 'Mis Proyectos')

@section('content')
    <div class="container-fluid">
        <h1>Mis Proyectos como Docente</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($proyectos->isEmpty())
            <p>Aún no has creado ningún proyecto.</p>
            <a href="{{ route('proyectos.create') }}" class="btn btn-primary">Crear Nuevo Proyecto</a>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Estado</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin Estimada</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($proyectos as $proyecto)
                        <tr>
                            <td>{{ $proyecto->titulo }}</td>
                            <td>{{ $proyecto->estado }}</td>
                            <td>{{ \Carbon\Carbon::parse($proyecto->fecha_inicio)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($proyecto->fecha_fin_estimada)->format('d/m/Y') }}</td>
                            <td>
                                {{-- Aquí hemos corregido los enlaces a las rutas 'show' y 'edit' --}}
                                <a href="{{ route('proyectos.edit', $proyecto->proyecto_id) }}" class="btn btn-sm btn-warning">Editar</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $proyectos->links() }} {{-- Para la paginación --}}
        @endif
    </div>
@endsection