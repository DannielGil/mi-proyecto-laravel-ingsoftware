<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoordinadorController extends Controller
{
    public function index()
    {
        return view('coordinador'); // Apunta a coordinador.blade.php
    }
}