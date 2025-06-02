
<!DOCTYPE html>
<html>
<head>
    <title>Recuperar contraseña</title>
</head>
<body>
    <h1>¿Olvidaste tu contraseña?</h1>
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <label for="email">Correo electrónico:</label>
        <input type="email" name="email" required>
        <br>
        <button type="submit">Enviar enlace de restablecimiento</button>
    </form>
    @if (session('status'))
        <p style="color:green;">{{ session('status') }}</p>
    @endif
    @if ($errors->any())
        <ul style="color:red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    <a href="{{ route('login') }}">Volver al login</a>
</body>
</html>