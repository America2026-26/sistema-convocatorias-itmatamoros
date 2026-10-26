@extends('layouts.app')

@section('title', 'Panel de Administración - Sistema de Convocatorias')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Panel de administración</h1>
            <p class="mt-1 text-sm text-gray-600">Resumen del sistema y herramientas de administración.</p>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
            @foreach ([
                ['Convocatorias creadas', $convocatoriasCount, route('home')],
                ['Usuarios en el sistema', $usersCount, route('admin.users.index')],
                ['Postulaciones pendientes', $pendingCount, null],
            ] as [$label, $value, $url])
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-600">{{ $label }}</p>
                    <p class="mt-3 text-3xl font-bold text-rose-950">{{ number_format($value) }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2">
            <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm hover:border-rose-700">
                <h2 class="font-bold text-gray-900">Administrar cuentas</h2>
                <p class="mt-1 text-sm text-gray-600">Crear o editar cuentas de usuarios y dictaminadores.</p>
            </a>
            <a href="{{ route('admin.audit-logs.index') }}" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm hover:border-rose-700">
                <h2 class="font-bold text-gray-900">Historial de cambios</h2>
                <p class="mt-1 text-sm text-gray-600">Consultar eventos del sistema y sus responsables.</p>
            </a>
        </div>
    </section>
@endsection
