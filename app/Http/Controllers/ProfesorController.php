<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfesorController extends Controller
{
    public function index()
    {
        return view('profesores'); // Apunta a profesores.blade.php
    }
}