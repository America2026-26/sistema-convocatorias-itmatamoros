<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        // Si es administrador, redirigir a la gestión de expedientes
        if ($user->role === 'admin') {
            return redirect()->route('admin.expedientes.index');
        }

        // Si es aspirante o usuario normal, mostrar el dashboard institucional
        return view('dashboard');
    }
}
