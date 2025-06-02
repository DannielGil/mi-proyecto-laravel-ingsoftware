@extends('layouts.app')

@section('page_title', 'Crear Nuevo Proyecto')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Formulario para Agregar Proyecto') }}</div>

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

                    <form method="POST" action="{{ route('proyectos.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título del Proyecto</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" value="{{ old('titulo') }}" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required>{{ old('descripcion') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', date('Y-m-d')) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_fin_estimada" class="form-label">Fecha de Fin Estimada</label>
                            <input type="date" class="form-control" id="fecha_fin_estimada" name="fecha_fin_estimada" value="{{ old('fecha_fin_estimada') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="presupuesto" class="form-label">Presupuesto ($)</label>
                            <input type="number" step="0.01" class="form-control" id="presupuesto" name="presupuesto" value="{{ old('presupuesto') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="">Seleccionar estado</option>
                                <option value="Pendiente de Validación" {{ old('estado') == 'Pendiente de Validación' ? 'selected' : '' }}>Pendiente de Validación</option>
                                <option value="Borrador" {{ old('estado') == 'Borrador' ? 'selected' : '' }}>Borrador</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="departamento_id" class="form-label">Departamento</label>
                            <select class="form-select" id="departamento_id" name="departamento_id" required>
                                <option value="">Seleccionar departamento</option>
                                @foreach ($departamentos as $departamento)
                                    <option value="{{ $departamento->id }}" {{ old('departamento_id') == $departamento->id ? 'selected' : '' }}>
                                        {{ $departamento->nombre_departamento }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="adjunto" class="form-label">Subir Archivo Adjunto (Opcional)</label>
                            <input class="form-control" type="file" id="adjunto" name="adjunto">
                            <div class="form-text">Formatos permitidos: PDF, Word, Excel, Imágenes (Max 5MB).</div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion_adjunto" class="form-label">Descripción del Adjunto (Opcional)</label>
                            <input type="text" class="form-control" id="descripcion_adjunto" name="descripcion_adjunto" value="{{ old('descripcion_adjunto') }}" placeholder="Ej: Propuesta inicial del proyecto">
                        </div>

                        <button type="submit" class="btn btn-primary" style="background-color: var(--primary-color);">Crear Proyecto</button>
                        {{-- ¡CAMBIO AQUÍ! Redirigir al dashboard del profesor --}}
                        <a href="{{ route('profesores.dashboard') }}" class="btn btn-secondary">Cancelar y Volver</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection