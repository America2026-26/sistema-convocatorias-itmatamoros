@extends('layouts.app')

@section('title', 'Registro de Aspirante - Sistema de Convocatorias')

@section('content')
    <div class="flex min-h-[70vh] flex-col items-center justify-center px-4 py-10 sm:px-6 sm:py-12 lg:px-8">
        <div class="mb-5 w-full max-w-2xl">
            <a href="{{ route('login') }}" aria-label="Volver al inicio de sesión"
                class="inline-flex h-10 w-10 items-center justify-center rounded-full text-gray-600 transition hover:bg-gray-200 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-rose-900 focus:ring-offset-2">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m15 18-6-6 6-6M9 12h12" />
                </svg>
            </a>
        </div>
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
            <h1 class="text-2xl font-extrabold leading-tight tracking-tight text-gray-900 sm:text-3xl">Registro de Cuenta de Aspirante</h1>
            <p class="mx-auto mt-2 max-w-xl text-sm leading-6 text-gray-600">Ingresa tus datos personales para solicitar plazas docentes o no docentes</p>
        </div>

        <div class="w-full max-w-2xl rounded-2xl border border-gray-200 bg-white px-5 py-7 shadow-lg sm:px-8 sm:py-8">
            <form action="{{ route('register.store') }}" method="POST" class="space-y-5">
                @csrf
                @if ($errors->any())
                    <div role="alert" class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                        <p class="mb-2 font-semibold">Revisa los datos del formulario:</p>
                        <ul class="list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label for="nombre" class="block text-xs font-semibold text-gray-700 uppercase mb-1">Nombre(s)</label>
                    <input id="nombre" type="text" name="nombre" value="{{ old('nombre') }}" maxlength="100" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-rose-900 focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="apellido_paterno" class="block text-xs font-semibold text-gray-700 uppercase mb-1">Apellido Paterno</label>
                        <input id="apellido_paterno" type="text" name="apellido_paterno" value="{{ old('apellido_paterno') }}" maxlength="100" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-rose-900 focus:outline-none">
                    </div>
                    <div>
                        <label for="apellido_materno" class="block text-xs font-semibold text-gray-700 uppercase mb-1">Apellido Materno</label>
                        <input id="apellido_materno" type="text" name="apellido_materno" value="{{ old('apellido_materno') }}" maxlength="100" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-rose-900 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="curp" class="block text-xs font-semibold text-gray-700 uppercase mb-1">CURP</label>
                        <input id="curp" type="text" name="curp" value="{{ old('curp') }}" maxlength="18" minlength="18" required placeholder="18 caracteres"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm uppercase focus:ring-2 focus:ring-rose-900 focus:outline-none">
                    </div>
                    <div>
                        <label for="rfc" class="block text-xs font-semibold text-gray-700 uppercase mb-1">RFC</label>
                        <input id="rfc" type="text" name="rfc" value="{{ old('rfc') }}" maxlength="13" minlength="12" required placeholder="12 o 13 caracteres"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm uppercase focus:ring-2 focus:ring-rose-900 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label for="tipo_usuario" class="block text-xs font-semibold text-gray-700 uppercase mb-1">Tipo de Perfil</label>
                    <select id="tipo_usuario" name="tipo_usuario" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-rose-900 focus:outline-none">
                        <option value="aspirante_docente">Aspirante a Plaza Docente</option>
                        <option value="aspirante_no_docente">Aspirante a Plaza No Docente / Administrativa</option>
                    </select>
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-700 uppercase mb-1">Correo Electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="correo@ejemplo.com"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-rose-900 focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-gray-700 uppercase mb-1">Contraseña</label>
                        <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password" placeholder="••••••••"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-rose-900 focus:outline-none">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-gray-700 uppercase mb-1">Confirmar Contraseña</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password" placeholder="••••••••"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-rose-900 focus:outline-none">
                    </div>
                </div>

                <button type="submit" class="w-full bg-rose-900 hover:bg-rose-800 text-white font-semibold py-2.5 rounded-lg shadow-md transition duration-200 text-sm">
                    Registrar Cuenta e Iniciar Sesión
                </button>
            </form>

            <div class="mt-6 text-center border-t border-gray-100 pt-4">
                <p class="text-xs text-gray-600">
                    ¿Ya tienes una cuenta registrada? 
                    <a href="{{ route('login') }}" class="font-bold text-rose-900 hover:underline">Inicia Sesión aquí</a>
                </p>
            </div>
        </div>

    </div>
@endsection