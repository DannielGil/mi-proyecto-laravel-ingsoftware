@extends('layouts.app')

{{-- Puedes definir un título específico para esta página --}}
@section('page_title', 'Dashboard de Profesor')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    {{-- Muestra un mensaje de bienvenida personalizado --}}
                    <h3>Bienvenido, Profesor {{ Auth::user()->name }}</h3>
                </div>

                <div class="card-body">
                    {{-- Mensaje de éxito de la sesión, útil después de crear un proyecto --}}
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <p>Desde aquí puedes gestionar tus actividades como profesor dentro del sistema.</p>
                    {{-- Puedes añadir más secciones o tarjetas aquí según las necesidades del profesor --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Opcional: Si necesitas iconos de Font Awesome, asegúrate de que estén incluidos en tu layouts.app --}}
@push('scripts')
<script>
    // Cualquier script específico para el dashboard del profesor puede ir aquí
    // Por ejemplo, gráficos, notificaciones en tiempo real, etc.
</script>
@end