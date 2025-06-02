<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'usuarios_passwords'), // <-- CAMBIADO: Renombrado el broker de passwords para ser más claro
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here. Here are the two basic guards you're most likely to use:
    |
    | web: Uses session storage and the "users" provider.
    | api: Uses token storage and the "users" provider.
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'usuarios_provider', // <-- CAMBIADO: Apunta al nuevo provider
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication providers have a "driver" that defines how they retrieve
    | users from your database or other storage mechanisms. You may add a new
    | driver as required or even implement your own custom drivers.
    |
    | Here are the two basic providers you're most likely to use:
    |
    | eloquent: Uses the Eloquent ORM to retrieve users.
    | database: Uses the query builder to retrieve users.
    |
    */

    'providers' => [
        'usuarios_provider' => [ // <-- CAMBIADO: Nuevo nombre para el provider
            'driver' => 'eloquent',
            'model' => App\Models\Usuario::class, // <-- CRUCIAL: Apunta a tu modelo Usuario
        ],

        // Si ya tenías un 'users' provider con App\Models\User::class,
        // puedes eliminarlo o comentarlo si no lo necesitas más.
        // 'users' => [
        //     'driver' => 'eloquent',
        //     'model' => App\Models\User::class,
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model being used to reset passwords.
    |
    | Here are the two basic configurations you're most likely to use:
    |
    | users: The default password reset configuration.
    |
    */

    'passwords' => [
        'usuarios_passwords' => [ // <-- CAMBIADO: Nuevo nombre para el broker de passwords
            'provider' => 'usuarios_provider', // <-- CAMBIADO: Apunta al nuevo provider
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the number of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];