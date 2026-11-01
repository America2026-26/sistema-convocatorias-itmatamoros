<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Convocatoria;

class CalendarioController extends Controller
{
    public function index()
    {
        // Obtener convocatorias activas o fechas clave del proceso institucional
        $convocatorias = Convocatoria::where('status', 'activa')->get();

        return view('calendario.index', compact('convocatorias'));
    }
}