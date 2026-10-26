@extends('layouts.app')

@section('title', 'Iniciar Sesión - Sistema de Convocatorias')

@section('content')
    <div class="flex min-h-[70vh] flex-col items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
        
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-6">
            <div class="inline-block bg-rose-950 text-white font-bold text-2xl p-3 rounded-lg shadow-md mb-2">
                ITM
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900">Iniciar Sesión</h2>
            <p class="text-xs text-gray-600 mt-1">Accede a tu panel de seguimiento de expediente y dictámenes</p>
        </div>

        <div class="bg-white py-8 px-6 shadow-xl rounded-2xl sm:max-w-md sm:w-full border border-gray-200">
            <form action="{{ route('login.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-700 uppercase mb-1">Correo Electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="correo@ejemplo.com"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-rose-900 focus:outline-none">
                    @error('email')
                        <p class="mt-1 text-xs text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-700 uppercase mb-1">Contraseña</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-rose-900 focus:outline-none">
                </div>

                <button type="submit" class="group inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-900/15 transition duration-200 hover:-translate-y-0.5 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2">
                    <svg class="h-5 w-5 transition-transform group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4m-4-4 4-5-4-5m4 5H3" />
                    </svg>
                    Ingresar al sistema
                </button>
            </form>

            <div class="mt-6 text-center border-t border-gray-100 pt-4">
                <p class="text-xs text-gray-600">
                    ¿Aún no tienes cuenta? 
                    <a href="{{ route('register') }}" class="font-bold text-rose-900 hover:underline">Regístrate aquí</a>
                </p>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="/" class="text-xs text-gray-500 hover:text-gray-800">&larr; Volver a las convocatorias</a>
        </div>
    </div>
@endsection