@extends('layouts.app')

@section('title', 'Panel del Aspirante - Sistema de Convocatorias ITM')

@section('content')
    <section class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <header class="flex flex-col gap-4 rounded-xl border border-gray-200 bg-white p-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-rose-900">Panel del aspirante</p>
                <h1 class="mt-1 text-2xl font-bold text-gray-950">Hola, {{ auth()->user()->name }}</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Consulta convocatorias, revisa el estado de tus postulaciones y consulta tus notificaciones.
                </p>
            </div>
            <nav aria-label="Acciones del aspirante" class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('home') }}#convocatorias" class="rounded-lg bg-rose-900 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-rose-800">
                    Ver convocatorias
                </a>
                <a href="{{ route('postulaciones.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Mis postulaciones
                </a>
            </nav>
        </header>

        @if (session('status'))
            <p role="status" class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-900">
                {{ session('status') }}
            </p>
        @endif

        <div class="grid gap-4 sm:grid-cols-2">
            <article class="rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm font-medium text-gray-600">Mis postulaciones</p>
                <p class="mt-2 text-3xl font-bold text-gray-950">{{ number_format($postulaciones->total()) }}</p>
                <p class="mt-1 text-xs text-gray-500">Expedientes enviados</p>
            </article>
            <article class="rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm font-medium text-gray-600">Notificaciones nuevas</p>
                <p class="mt-2 text-3xl font-bold text-gray-950">{{ number_format($unreadNotificationsCount) }}</p>
                <a href="{{ route('notifications.index') }}" class="mt-1 inline-block text-xs font-semibold text-rose-900 hover:underline">
                    Consultar notificaciones
                </a>
            </article>
        </div>

        <section class="space-y-4 rounded-xl border border-gray-200 bg-white p-5 sm:p-6" aria-labelledby="applications-heading">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 id="applications-heading" class="text-lg font-bold text-gray-950">Seguimiento de postulaciones</h2>
                    <p class="mt-1 text-sm text-gray-600">Consulta el estado de tus expedientes enviados.</p>
                </div>
                <a href="{{ route('postulaciones.index') }}" class="text-sm font-semibold text-rose-900 hover:underline">
                    Ver todas mis postulaciones
                </a>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse ($postulaciones as $postulacion)
                    <article class="flex flex-col gap-3 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0">
                            <span class="inline-flex rounded bg-rose-50 px-2 py-0.5 text-xs font-semibold text-rose-900">
                                {{ $postulacion->convocatoria?->tipo_personal ?? 'Tipo no disponible' }}
                            </span>
                            <h3 class="mt-1.5 font-semibold text-gray-950">{{ $postulacion->convocatoria?->titulo ?? 'Convocatoria no disponible' }}</h3>
                            <div class="mt-1.5 flex flex-wrap items-center gap-2 text-xs text-gray-600">
                                <span>Enviada el {{ $postulacion->created_at->format('d/m/Y') }}</span>
                                <span aria-hidden="true" class="text-gray-300">·</span>
                                <span @class([
                                    'rounded px-2 py-0.5 font-semibold',
                                    'bg-amber-100 text-amber-900' => $postulacion->estatus === 'pendiente',
                                    'bg-emerald-100 text-emerald-900' => $postulacion->estatus === 'aprobada',
                                    'bg-rose-100 text-rose-900' => $postulacion->estatus === 'rechazada',
                                    'bg-violet-100 text-violet-900' => $postulacion->estatus === 'devuelta',
                                    'bg-gray-100 text-gray-700' => ! in_array($postulacion->estatus, ['pendiente', 'aprobada', 'rechazada', 'devuelta'], true),
                                ])>{{ $postulacion->estatus === 'devuelta' ? 'Devuelta para edición' : ucfirst($postulacion->estatus) }}</span>
                            </div>
                        </div>
                        <a href="{{ route('postulaciones.show', $postulacion) }}" class="shrink-0 rounded-lg border border-gray-300 px-3 py-2 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Ver expediente enviado
                        </a>
                    </article>
                @empty
                    <div class="rounded-lg bg-gray-50 px-4 py-8 text-center">
                        <p class="font-semibold text-gray-800">Aún no tienes postulaciones</p>
                        <p class="mt-1 text-sm text-gray-600">Explora las convocatorias disponibles para comenzar.</p>
                        <a href="{{ route('home') }}#convocatorias" class="mt-3 inline-block text-sm font-semibold text-rose-900 hover:underline">
                            Ver convocatorias
                        </a>
                    </div>
                @endforelse
            </div>

            <div>{{ $postulaciones->links() }}</div>
        </section>
    </section>
@endsection