{{-- resources/views/errors/unauthorized.blade.php --}}

@extends('layouts.app') {{-- Asegúrate de que este sea el layout principal de tu aplicación --}}

@section('page_title', 'Acceso No Autorizado')

@section('content')
    <div class="container text-center py-5" style="min-height: 70vh; display: flex; flex-direction: column; justify-content: center; align-items: center;">
        <h1 class="display-4 text-danger mb-3">
            <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.5em;"></i> Acceso No Autorizado
        </h1>
        <p class="lead mb-4">Lo sentimos, no tienes los permisos necesarios para acceder a esta página.</p>
        <hr class="my-4 w-50">
        <p class="text-muted">Si crees que esto es un error, por favor contacta al administrador del sistema.</p>
        
        <div class="mt-4">
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary me-2">
                <i class="bi bi-arrow-left"></i> Volver a la página anterior
            </a>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush