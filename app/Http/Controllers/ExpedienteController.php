<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Postulacion;

class ExpedienteController extends Controller
{
    public function estado()
    {
        $user = Auth::user();
        
        // Obtenemos las postulaciones de forma segura consultando directamente el modelo Postulacion
        $postulaciones = Postulacion::where('user_id', $user->id)
            ->with('convocatoria')
            ->latest()
            ->get();

        return view('expediente.estado', compact('user', 'postulaciones'));
    }
}