
<!DOCTYPE html>
<html>
<head>
    <title>Restablecer contraseña</title>
</head>
<body>
    <h1>Restablecer contraseña</h1>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ request('email', $email ?? '') }}">
        <label>Nueva contraseña:</label>
        <input type="password" name="password" required>
        <br>
        <label>Confirmar contraseña:</label>
        <input type="password" name="password_confirmation" required>
        <br>
        <button type="submit">Restablecer</button>
    </form>
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