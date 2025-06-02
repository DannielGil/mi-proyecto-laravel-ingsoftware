@extends('layouts.app') {{-- O el layout base que uses para tu dashboard --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Crear Nuevo Usuario') }}</div>

                <div class="card-body">
                    {{-- Mensajes de éxito/error (si los pasas desde el controlador) --}}
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

                    <form method="POST" action="{{ route('usuarios.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required autofocus>
                            @error('nombre')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control @error('apellido') is-invalid @enderror" id="apellido" name="apellido" value="{{ old('apellido') }}" required>
                            @error('apellido')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        {{-- --- Bloque de Departamento Corregido --- --}}
                        <div class="mb-3">
                            <label for="departamento_id" class="form-label">Departamento</label>
                            <select class="form-select @error('departamento_id') is-invalid @enderror" id="departamento_id" name="departamento_id"> {{-- id y name corregidos a departamento_id --}}
                                <option value="">Seleccione un departamento</option>
                                @foreach ($departamentos as $departamento)
                                    <option value="{{ $departamento->departamento_id }}" {{ old('departamento_id') == $departamento->departamento_id ? 'selected' : '' }}>
                                        {{ $departamento->nombre }} {{-- ¡Aquí está el cambio clave! Usamos 'nombre' --}}
                                    </option>
                                @endforeach
                            </select>
                            @error('departamento_id') {{-- Error handling para departamento_id --}}
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        {{-- --- Fin Bloque de Departamento Corregido --- --}}

                        <div class="mb-3">
                            <label for="roles" class="form-label">Roles</label>
                            <select multiple class="form-select @error('roles') is-invalid @enderror" id="roles" name="roles[]">
                                <option value="">Seleccione uno o varios roles</option> {{-- Opcional: Añadir un placeholder --}}
                                @foreach ($roles as $rol)
                                    <option value="{{ $rol->rol_id }}" {{ in_array($rol->rol_id, old('roles', [])) ? 'selected' : '' }}>
                                        {{ $rol->nombre_rol }}
                                    </option>
                                @endforeach
                            </select>
                            @error('roles')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="form-text">Mantén presionado Ctrl (o Cmd en Mac) para seleccionar múltiples roles.</div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1" {{ old('activo', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">Activo</label>
                        </div>

                        <button type="submit" class="btn btn-success">Crear Usuario</button>
                        {{-- Botón "Volver al Dashboard" --}}
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary ms-2">Volver al Dashboard</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection