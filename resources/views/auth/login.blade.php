@extends('layouts.app')

@section('title', 'Iniciar Sesión - Sistema de Convocatorias')

@section('content')
    <div class="flex min-h-[70vh] flex-col items-center justify-center px-4 py-10 sm:px-6 sm:py-12 lg:px-8">
        <div class="mb-8 w-full max-w-2xl text-center">
            <div class="mx-auto mb-6 flex w-full flex-col items-center gap-3 sm:flex-row sm:justify-center sm:gap-4">
                <img
                    src="https://dev.matamoros.tecnm.mx/tecnm_itm/sitio/drive/logo-del-tec-guindo-289x300.png"
                    alt="Logotipo del Instituto Tecnológico de Matamoros"
                    class="h-16 w-16 shrink-0 rounded-2xl bg-white p-2 object-contain shadow-sm ring-1 ring-gray-200"
                >
                <span class="max-w-xs text-lg font-bold leading-tight text-gray-900 sm:max-w-none sm:text-xl">
                    Instituto Tecnológico de Matamoros
                </span>
            </div>
            <h1 class="text-2xl font-extrabold leading-tight tracking-tight text-gray-900 sm:text-3xl">Iniciar Sesión</h1>
            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-600">Accede a tu panel de seguimiento de expediente y dictámenes</p>
        </div>

        <div class="w-full max-w-md rounded-2xl border border-gray-200 bg-white px-5 py-7 shadow-lg sm:px-8 sm:py-8">
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
                    Iniciar Sesión
                </button>
            </form>

            <div class="mt-6 text-center border-t border-gray-100 pt-4">
                <p class="text-xs text-gray-600">
                    ¿Aún no tienes cuenta? 
                    <a href="{{ route('register') }}" class="font-bold text-rose-900 hover:underline">Regístrate aquí</a>
                </p>
            </div>
        </div>

        
    </div>
@endsection