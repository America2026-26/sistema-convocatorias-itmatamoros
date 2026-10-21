<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// 1. Landing Page Pública (Acceso libre a convocatorias vigentes)
Route::get('/', function () {
    $convocatorias = [
        (object)[
            'id_convocatoria' => 1,
            'titulo' => 'Convocatoria Docente de Cátedra e Investigación 2026',
            'tipo_concurso' => 'Público Abierto',
            'tipo_personal' => 'Docente',
            'categoria_nivel' => 'Profesor Titular A',
            'plaza_disponible' => '3 Plazas',
            'jornada_horario' => 'Tiempo Completo (40 hrs/semana)',
            'salario' => 24500.00,
            'fecha_inicio' => '2026-10-01',
            'fecha_fin' => '2026-11-15',
            'estatus' => 'Activa',
            'requisitos_especificos' => 'Título de Maestría o Doctorado en Ingeniería o Área Afín, Cédula Profesional, RFC, CURP.'
        ],
        (object)[
            'id_convocatoria' => 2,
            'titulo' => 'Convocatoria de Personal No Docente / Administrativo',
            'tipo_concurso' => 'Interno / Abierto',
            'tipo_personal' => 'No Docente',
            'categoria_nivel' => 'Técnico Administrativo C',
            'plaza_disponible' => '1 Plaza',
            'jornada_horario' => 'Medio Tiempo (20 hrs/semana)',
            'salario' => 12800.00,
            'fecha_inicio' => '2026-10-10',
            'fecha_fin' => '2026-10-30',
            'estatus' => 'Activa',
            'requisitos_especificos' => 'Título o Cédula Técnica en Sistemas/Computación, Experiencia mínima de 1 año.'
        ]
    ];

    return view('welcome', compact('convocatorias'));
});

// 2. Pantalla de Iniciar Sesión (GET)
Route::get('/login', function () {
    // Si ya inició sesión previamente, lo mandamos directo al dashboard
    if (session()->has('usuario_autenticado')) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
})->name('login');

// 3. Pantalla de Registro (GET)
Route::get('/registro', function () {
    if (session()->has('usuario_autenticado')) {
        return redirect()->route('dashboard');
    }
    return view('auth.register');
})->name('register');

// 4. Procesar Inicio de Sesión (POST)
Route::post('/login', function (Request $request) {
    // Registramos la sesión activa del usuario
    session(['usuario_autenticado' => true]);
    return redirect()->route('dashboard');
})->name('login.store');

// 5. Procesar Registro (POST)
Route::post('/registro', function (Request $request) {
    // Guardamos la sesión activa tras el registro
    session(['usuario_autenticado' => true]);
    return redirect()->route('dashboard');
})->name('register.store');

// 6. Panel Privado / Dashboard (Ruta Protegida)
Route::get('/dashboard', function () {
    // Verificamos si NO ha iniciado sesión
    if (!session()->has('usuario_autenticado')) {
        // Redirige al login bloqueando la vista protegida
        return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder al panel.');
    }

    return view('dashboard');
})->name('dashboard');

// 7. Cerrar Sesión (Destruye la sesión y redirige al inicio)
Route::get('/logout', function () {
    session()->forget('usuario_autenticado');
    return redirect()->route('login');
})->name('logout');