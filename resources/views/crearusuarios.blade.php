
<!-- filepath: resources/views/crearusuarios.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Crear Usuario</title>
</head>
<body>
    <h1>Crear Usuario</h1>
    <form method="POST" action="{{ route('usuarios.store') }}">
        @csrf
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required>
        <br>
        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" required>
        <br>
        <label for="email">Correo electrónico:</label>
        <input type="email" name="email" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" required>
        <br>
        <label for="password_confirmation">Confirmar contraseña:</label>
        <input type="password" name="password_confirmation" required>
        <br>
        <label for="activo">Activo:</label>
        <input type="checkbox" name="activo" value="1">
        <br>
        <label for="departamento_id">Departamento (opcional):</label>
        <input type="text" name="departamento_id">
        <br>
        <button type="submit">Crear</button>
    </form>
    @if ($errors->any())
        <ul style="color:red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</body>
</html>