@extends('layouts.app')

@section('page_title', 'Editar Proyecto')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Editar Proyecto') }}: {{ $proyecto->titulo }}</div>

                <div class="card-body">
                    {{-- Mensajes de validación y de sesión (éxito/error) --}}
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('proyectos.update', $proyecto->proyecto_id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- Importante para simular una petición PUT --}}

                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título del Proyecto</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" value="{{ old('titulo', $proyecto->titulo) }}" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required>{{ old('descripcion', $proyecto->descripcion) }}</textarea>
                        </div>


                        <div class="mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', $proyecto->fecha_inicio ? \Carbon\Carbon::parse($proyecto->fecha_inicio)->format('Y-m-d') : '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_fin_estimada" class="form-label">Fecha de Fin Estimada</label>
                            <input type="date" class="form-control" id="fecha_fin_estimada" name="fecha_fin_estimada" value="{{ old('fecha_fin_estimada', $proyecto->fecha_fin_estimada ? \Carbon\Carbon::parse($proyecto->fecha_fin_estimada)->format('Y-m-d') : '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="presupuesto" class="form-label">Presupuesto ($)</label>
                            <input type="number" step="0.01" class="form-control" id="presupuesto" name="presupuesto" value="{{ old('presupuesto', $proyecto->presupuesto) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="">Seleccionar estado</option>
                                <option value="Activo" {{ old('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Inactivo" {{ old('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>


                        <div class="mb-3">
                            <label for="departamento_id" class="form-label">Departamento</label>
                            <select class="form-select" id="departamento_id" name="departamento_id" required>
                                <option value="">Seleccionar departamento</option>
                                @foreach ($departamentos as $departamento)
                                    <option value="{{ $departamento->departamento_id }}" {{ old('departamento_id') == $departamento->departamento_id ? 'selected' : '' }}>
                                        {{ $departamento->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="adjuntos" class="form-label">Añadir Nuevos Archivos Adjuntos (Opcional)</label>
                            <input class="form-control" type="file" id="adjuntos" name="adjuntos[]" multiple>
                            <div class="form-text">Formatos permitidos: PDF, Word, Excel, Imágenes (Max 5MB por archivo).</div>
                            @if ($proyecto->adjuntos->count() > 0)
                                <p class="mt-2">Archivos adjuntos actuales:</p>
                                <ul>
                                    @foreach ($proyecto->adjuntos as $adjunto)
                                        <li class="d-flex align-items-center mb-1">
                                            {{-- ¡CORRECCIÓN AQUÍ! Cambia la ruta del enlace a 'proyectos.descargar_adjunto' --}}
                                            <a href="{{ route('proyectos.descargar_adjunto', $adjunto->id) }}" target="_blank" class="me-2">
                                                {{ $adjunto->nombre_original }}
                                            </a>
                                            
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary" style="background-color: var(--primary-color);">Actualizar Proyecto</button>
                        <a href="{{ route('proyectos.mis_proyectos') }}" class="btn btn-secondary">Cancelar</a>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection