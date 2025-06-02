<!DOCTYPE html>
<html>
<head>
    <title>Restablecer Contraseña</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9; }
        .button { display: inline-block; padding: 10px 20px; margin-top: 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hola, {{ $userName }}</h2>

        <p>Has recibido este correo porque hemos recibido una solicitud de restablecimiento de contraseña para tu cuenta.</p>

        <p>Por favor, haz clic en el siguiente botón para restablecer tu contraseña:</p>

        <a href="{{ $resetLink }}" class="button">Restablecer Contraseña</a>

        <p>Si no solicitaste un restablecimiento de contraseña, puedes ignorar este correo electrónico de forma segura.</p>

        <p>Saludos,<br>
        {{ config('app.name') }}</p>
    </div>
</body>
</html>