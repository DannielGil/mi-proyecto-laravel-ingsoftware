<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController; // Importa la clase base de Laravel

abstract class Controller extends BaseController // ¡IMPORTANTE: Ahora extiende BaseController!
{
    use AuthorizesRequests, ValidatesRequests;
}