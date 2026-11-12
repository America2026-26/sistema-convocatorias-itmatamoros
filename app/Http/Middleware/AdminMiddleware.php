<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica si el usuario ha iniciado sesión y si su rol es 'admin'
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        // Si no es admin, lo reenvía a su panel normal o al inicio
        return redirect()->route('expediente.estado')->with('error', 'Acceso no autorizado.');
    }
}
