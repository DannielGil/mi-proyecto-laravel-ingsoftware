@extends('layouts.app')

{{-- Define el título que aparecerá en la navbar del main-content --}}
@section('page_title', 'Bienvenido')


@section('content')
    {{-- TODO EL CONTENIDO ESPECÍFICO DE LA VISTA DEL COORDINADOR VA AQUÍ --}}

<div class="welcome-section">
    {{-- Aquí se muestra el nombre y apellido del usuario --}}
    <h1>Coordinador {{ Auth::user()->nombre }} {{ Auth::user()->apellido }}</h1>
    <p>Aquí puedes gestionar y validar proyectos, y supervisar el progreso de los docentes</p>
</div>

    {{-- Puedes añadir más secciones o tarjetas aquí para el contenido del coordinador --}}

@endsection