<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Aquí puedes cargar datos para tu dashboard
        // Por ahora, simplemente devolveremos una vista.
        return view('dashboard');
    }
}
