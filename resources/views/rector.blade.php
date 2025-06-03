@extends('layouts.app')

{{-- Define el título que aparecerá en la navbar del main-content --}}
@section('page_title', 'Bienvenido')


@section('content')
    {{-- TODO EL CONTENIDO ESPECÍFICO DE LA VISTA DEL COORDINADOR VA AQUÍ --}}

<div class="welcome-section">
    {{-- Aquí se muestra el nombre y apellido del usuario --}}
    <h1>Rector {{ Auth::user()->nombre }} {{ Auth::user()->apellido }}</h1>
    <p>Aquí puedes validar las métricas de los proyectos</p>
</div>

    {{-- Puedes añadir más secciones o tarjetas aquí para el contenido del coordinador --}}

@endsection