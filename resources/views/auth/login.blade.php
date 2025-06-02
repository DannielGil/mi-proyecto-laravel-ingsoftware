<!DOCTYPE html>
<html lang="es">
<head>
    <title>Login - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="utf-8" />
    <meta property="twitter:card" content="summary_large_image" />

    {{-- Elimina estas dos líneas si no las usas o ya las tienes en un CSS global --}}
    {{-- <link rel="stylesheet" href="http://localhost:8000/css/estilos.css" /> --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}" /> --}}

    {{-- Enlaces a CDN para animaciones y fuentes (mantener si son necesarios) --}}
    <link rel="stylesheet" href="https://unpkg.com/animate.css@4.1.1/animate.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" data-tag="font" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=STIX+Two+Text:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" data-tag="font" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" data-tag="font" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" data-tag="font" />
    <link rel="stylesheet" href="https://unpkg.com/@teleporthq/teleport-custom-scripts/dist/style.css" />

    <style>
        /* Variables CSS (similares a las del dashboard.css) */
        :root {
            --primary-color: #4CAF50; /* Verde del dashboard */
            --background-color: #f4f7f6; /* Fondo del dashboard */
            --card-background: #ffffff; /* Fondo de tarjetas del dashboard */
            --text-color: #333;
            --light-text-color: #666;
            --border-color: #eee;
            --shadow-color: rgba(0, 0, 0, 0.05);
        }

        body {
            font-family: 'Arial', sans-serif; /* Consistente con el dashboard */
            background-color: var(--background-color);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Para centrar el formulario verticalmente */
            margin: 0;
            line-height: 1.6;
        }

        #app {
            background-color: var(--card-background);
            padding: 2.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 15px var(--shadow-color); /* Sombra más pronunciada para el login */
            max-width: 450px; /* Ancho ajustable para el formulario */
            width: 100%;
            text-align: center; /* Centrar el título y los enlaces */
        }

        h1.thq-heading-1 {
            font-size: 2em; /* Tamaño similar al h2 del dashboard */
            color: var(--primary-color); /* Color primario para el título */
            margin-bottom: 1.5rem;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        label.thq-body-small {
            font-size: 0.9em;
            color: var(--light-text-color);
            text-align: left; /* Alinear etiquetas a la izquierda */
            margin-bottom: 0.25rem;
        }

        input.thq-input {
            padding: 0.8rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 1em;
            width: 100%; /* Asegurar que el input ocupe todo el ancho disponible */
            box-sizing: border-box; /* Incluir padding y borde en el ancho */
        }

        button.thq-button-filled {
            background-color: var(--primary-color);
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%; /* Asegurar que el botón ocupe todo el ancho */
            margin-top: 0.5rem; /* Ajuste de margen superior */
        }

        button.thq-button-filled:hover {
            background-color: #388E3C; /* Un verde un poco más oscuro al pasar el ratón */
        }

        a.thq-link {
            color: var(--secondary-color); /* Podrías definir un secondary-color o usar el primary */
            text-decoration: none;
            font-size: 0.9em;
            transition: color 0.3s ease;
        }

        a.thq-link:hover {
            color: var(--primary-color); /* Cambiar a primary al pasar el ratón */
            text-decoration: underline;
        }

        /* Estilos para mensajes de error/estado */
        .error-message {
            color: #d32f2f; /* Un rojo más oscuro */
            background-color: #ffebee;
            border: 1px solid #ef9a9a;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            text-align: left;
        }
        .error-message ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .status-message {
            color: #388e3c; /* Un verde oscuro */
            background-color: #e8f5e9;
            border: 1px solid #a5d6a7;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            text-align: left;
        }

        /* Media Queries para responsividad */
        @media (max-width: 600px) {
            #app {
                margin: 1rem; /* Menos margen en pantallas pequeñas */
                padding: 1.5rem;
            }

            h1.thq-heading-1 {
                font-size: 1.8em;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        {{-- Mensajes de error de Laravel --}}
        @if ($errors->any())
            <div class="error-message">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Mensajes de estado (ej. de restablecimiento de contraseña exitoso) --}}
        @if (session('status'))
            <div class="status-message">
                {{ session('status') }}
            </div>
        @endif

        <h1 class="thq-heading-1">Iniciar sesión</h1>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <label for="email" class="thq-body-small">Email:</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus class="thq-input">

            <label for="password" class="thq-body-small">Contraseña:</label>
            <input type="password" name="password" id="password" required class="thq-input">

            <button type="submit" class="thq-button-filled">Entrar</button>
        </form>

        <div style="text-align: center; margin-top: 1rem;">
            <a href="{{ route('password.request') }}" class="thq-link">¿Olvidaste la contraseña?</a>
        </div>
    </div>
</body>
</html>