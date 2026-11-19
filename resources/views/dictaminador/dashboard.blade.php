@extends('layouts.app')

@section('title', 'Panel del Dictaminador - Sistema de Convocatorias ITM')

@section('content')
    <section class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <header class="flex flex-col gap-6 rounded-2xl bg-gradient-to-br from-rose-950 via-rose-900 to-slate-900 p-6 text-white shadow-sm sm:p-8 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-2xl">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-200">Panel de dictaminación</p>
                <h1 class="mt-2 text-2xl font-bold sm:text-3xl">Hola, {{ auth()->user()->name }}</h1>
                <p class="mt-2 text-sm leading-6 text-rose-100">
                    Administra convocatorias, revisa expedientes y da seguimiento a las postulaciones recibidas.
                </p>
            </div>
            <nav aria-label="Acciones del dictaminador" class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('dictaminador.convocatorias.index') }}" class="rounded-xl bg-white px-4 py-2.5 text-center text-sm font-semibold text-rose-950 transition hover:bg-rose-50">
                    Revisar convocatorias
                </a> 
                <a href="{{ route('dictaminador.postulantes.index') }}" class="rounded-xl bg-white px-4 py-2.5 text-center text-sm font-semibold text-rose-950 transition hover:bg-rose-50">
                    Revisar postulantes
                </a>
            </nav>
        </header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <!-- <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-gray-600">Convocatorias registradas</p>
                <p class="mt-3 text-3xl font-bold text-gray-950">{{ number_format($convocatoriasCount) }}</p>
                <p class="mt-1 text-xs text-gray-500">Abiertas y concluidas</p>
            </article> -->
            <article class="rounded-2xl border border-emerald-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-emerald-900">Convocatorias vigentes</p>
                <p class="mt-3 text-3xl font-bold text-emerald-950">{{ number_format($convocatoriasActivasCount) }}</p>
                <p class="mt-1 text-xs text-emerald-800">Disponibles actualmente</p>
            </article>
            <article class="rounded-2xl border border-sky-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-sky-900">Postulantes</p>
                <p class="mt-3 text-3xl font-bold text-sky-950">{{ number_format($postulantesCount) }}</p>
                <p class="mt-1 text-xs text-sky-800">Con al menos una postulación</p>
            </article>
            <article class="rounded-2xl border border-amber-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-amber-950">Postulaciones pendientes</p>
                <p class="mt-3 text-3xl font-bold text-amber-950">{{ number_format($pendingCount) }}</p>
                <p class="mt-1 text-xs text-amber-900">Expedientes por revisar</p>
            </article>
        </div>

        <div class="grid gap-6 lg:grid-cols-5">
            <section class="space-y-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6 lg:col-span-3" aria-labelledby="pending-applications-heading">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 id="pending-applications-heading" class="text-lg font-bold text-gray-950">Postulaciones pendientes</h2>
                        <p class="mt-1 text-sm text-gray-600">Expedientes recientes que requieren dictamen.</p>
                    </div>
                    <a href="{{ route('dictaminador.postulantes.index') }}" class="text-sm font-semibold text-rose-900 hover:text-rose-700 hover:underline">
                        Ver todos los postulantes
                    </a>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse ($postulacionesPendientes as $postulacion)
                        <article class="flex flex-col gap-4 py-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="font-semibold text-gray-950">{{ $postulacion->user?->name ?? 'Usuario no disponible' }}</h3>
                                    <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-900">Pendiente</span>
                                </div>
                                <p class="mt-1 break-all text-sm text-gray-600">{{ $postulacion->user?->email ?? 'Correo no disponible' }}</p>
                                <p class="mt-1 text-sm text-gray-700">
                                    {{ $postulacion->convocatoria?->titulo ?? 'Convocatoria no disponible' }}
                                    <span class="text-gray-400">·</span>
                                    {{ $postulacion->convocatoria?->tipo_personal ?? 'Tipo no disponible' }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500">Recibida el {{ $postulacion->created_at->format('d/m/Y') }}</p>
                            </div>
                            <a href="{{ route('dictaminador.postulantes.show', $postulacion) }}" class="shrink-0 rounded-lg border border-rose-200 px-3 py-2 text-center text-sm font-semibold text-rose-900 transition hover:bg-rose-50">
                                Revisar expediente
                            </a>
                        </article>
                    @empty
                        <div class="rounded-xl bg-gray-50 px-4 py-8 text-center">
                            <p class="font-semibold text-gray-800">Todo al día</p>
                            <p class="mt-1 text-sm text-gray-600">No hay postulaciones pendientes de revisión.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="space-y-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6 lg:col-span-2" aria-labelledby="expiring-convocatorias-heading">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h2 id="expiring-convocatorias-heading" class="text-lg font-bold text-gray-950">Convocatorias a punto de vencer</h2>
                        <p class="mt-1 text-sm text-gray-600">Vencen dentro de los próximos siete días.</p>
                    </div>
                    <!-- <a href="{{ route('dictaminador.convocatorias.index') }}" class="shrink-0 text-sm font-semibold text-rose-900 hover:text-rose-700 hover:underline">
                        Administrar
                    </a> -->
                </div>

                <div class="space-y-3">
                    @forelse ($porVencer as $convocatoria)
                        <article class="rounded-xl border border-amber-200 bg-amber-50/60 p-4">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-gray-700">{{ $convocatoria->tipo_personal }}</span>
                                <span class="text-xs font-semibold text-amber-900">Vence {{ $convocatoria->fecha_fin->format('d/m/Y') }}</span>
                            </div>
                            <h3 class="mt-2 font-semibold leading-5 text-gray-950">{{ $convocatoria->titulo }}</h3>
                            <p class="mt-1 text-sm text-gray-700">{{ $convocatoria->postulaciones_count }} postulaciones recibidas</p>
                        </article>
                    @empty
                        <p class="rounded-xl bg-gray-50 px-4 py-8 text-center text-sm text-gray-600">
                            No hay convocatorias vigentes próximas a vencer.
                        </p>
                    @endforelse
                </div>
            </section>
        </div>

        <!-- <section class="space-y-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="recent-convocatorias-heading">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 id="recent-convocatorias-heading" class="text-lg font-bold text-gray-950">Convocatorias recientes</h2>
                    <p class="mt-1 text-sm text-gray-600">Estado y actividad de las convocatorias creadas durante los últimos siete días.</p>
                </div>
                <a href="{{ route('dictaminador.convocatorias.index') }}" class="text-sm font-semibold text-rose-900 hover:text-rose-700 hover:underline">
                    Ver todas las convocatorias
                </a>
            </div>

            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($nuevas as $convocatoria)
                    <article class="rounded-xl border border-gray-200 p-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-900">{{ $convocatoria->tipo_personal }}</span>
                            <span @class([
                                'rounded-full px-2.5 py-1 text-xs font-semibold',
                                'bg-emerald-100 text-emerald-900' => $convocatoria->estatus === 'Activa',
                                'bg-gray-100 text-gray-700' => $convocatoria->estatus !== 'Activa',
                            ])>{{ $convocatoria->estatus }}</span>
                        </div>
                        <h3 class="mt-3 font-semibold leading-5 text-gray-950">{{ $convocatoria->titulo }}</h3>
                        <p class="mt-2 text-sm text-gray-600">{{ $convocatoria->tipo_concurso }} · {{ $convocatoria->categoria_nivel }}</p>
                        <p class="mt-2 text-xs text-gray-500">
                            Vigencia: {{ $convocatoria->fecha_inicio->format('d/m/Y') }} – {{ $convocatoria->fecha_fin->format('d/m/Y') }}
                        </p>
                        <p class="mt-2 text-sm font-medium text-gray-700">{{ $convocatoria->postulaciones_count }} postulaciones</p>
                    </article>
                @empty
                    <p class="rounded-xl bg-gray-50 px-4 py-8 text-center text-sm text-gray-600 md:col-span-2 xl:col-span-3">
                        Todavía no hay convocatorias registradas.
                    </p>
                @endforelse
            </div>
        </section> -->

        
    </section>
@endsection
