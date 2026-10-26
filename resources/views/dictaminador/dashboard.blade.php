@extends('layouts.app')

@section('title', 'Panel del Dictaminador - Sistema de Convocatorias')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Panel del dictaminador</h1>
                <p class="mt-1 text-sm text-gray-600">Bienvenido, {{ auth()->user()->name }}. Aquí está tu resumen de trabajo.</p>
            </div>
            <a href="{{ route('dictaminador.convocatorias.create') }}" class="rounded-lg bg-rose-900 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-rose-800">
                Crear convocatoria
            </a>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            @foreach ([
                ['Convocatorias creadas', $convocatoriasCount, route('dictaminador.convocatorias.index')],
                ['Postulaciones pendientes', $pendingCount, route('dictaminador.postulantes.index')],
            ] as [$label, $value, $url])
                <a href="{{ $url }}" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:border-rose-700">
                    <p class="text-sm text-gray-600">{{ $label }}</p>
                    <p class="mt-3 text-3xl font-bold text-rose-950">{{ number_format($value) }}</p>
                </a>
            @endforeach
        </div>

        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="font-bold text-gray-900">Convocatorias nuevas</h2>
                    <a href="{{ route('dictaminador.convocatorias.index') }}" class="text-sm font-semibold text-rose-900 hover:underline">Ver todas</a>
                </div>
                <ul class="divide-y divide-gray-100">
                    @forelse ($nuevas as $convocatoria)
                        <li class="py-3">
                            <p class="font-semibold text-gray-900">{{ $convocatoria->titulo }}</p>
                            <p class="mt-1 text-xs text-gray-500">{{ $convocatoria->tipo_personal }} · {{ $convocatoria->created_at->format('d/m/Y') }}</p>
                        </li>
                    @empty
                        <li class="py-4 text-sm text-gray-500">No hay convocatorias recientes.</li>
                    @endforelse
                </ul>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="font-bold text-gray-900">Convocatorias a punto de vencer</h2>
                    <a href="{{ route('dictaminador.convocatorias.index') }}" class="text-sm font-semibold text-rose-900 hover:underline">Ver todas</a>
                </div>
                <ul class="divide-y divide-gray-100">
                    @forelse ($porVencer as $convocatoria)
                        <li class="py-3">
                            <p class="font-semibold text-gray-900">{{ $convocatoria->titulo }}</p>
                            <p class="mt-1 text-xs text-amber-700">Cierra el {{ $convocatoria->fecha_fin->format('d/m/Y') }}</p>
                        </li>
                    @empty
                        <li class="py-4 text-sm text-gray-500">No hay convocatorias por vencer esta semana.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('dictaminador.postulantes.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Revisar postulantes</a>
            <a href="{{ route('dictaminador.convocatorias.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Gestionar convocatorias</a>
        </div>
    </section>
@endsection
