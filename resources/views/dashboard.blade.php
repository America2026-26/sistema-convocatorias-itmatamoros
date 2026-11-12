@extends('layouts.app')

@section('title', 'Panel del Aspirante - Sistema de Convocatorias ITM')

@section('content')
    <section class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <header class="flex flex-col gap-6 rounded-2xl bg-gradient-to-br from-rose-950 via-rose-900 to-slate-900 p-6 text-white shadow-sm sm:p-8 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-2xl">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-200">Panel del aspirante</p>
                <h1 class="mt-2 text-2xl font-bold sm:text-3xl">Hola, {{ auth()->user()->name }}</h1>
                <p class="mt-2 text-sm leading-6 text-rose-100">
                    Consulta convocatorias, da seguimiento a tus postulaciones y revisa las notificaciones de tus trámites.
                </p>
            </div>
            <nav aria-label="Acciones del aspirante" class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('home') }}#convocatorias" class="rounded-xl bg-white px-4 py-2.5 text-center text-sm font-semibold text-rose-950 transition hover:bg-rose-50">
                    Ver convocatorias
                </a>
                <a href="{{ route('postulaciones.index') }}" class="rounded-xl border border-white/40 px-4 py-2.5 text-center text-sm font-semibold text-white transition hover:bg-white/10">
                    Mis postulaciones
                </a>
            </nav>
        </header>

        <div class="grid gap-4 sm:grid-cols-2">
            <article class="rounded-2xl border border-rose-200 bg-rose-50/70 p-5 shadow-sm">
                <p class="text-sm font-medium text-rose-950">Mis postulaciones</p>
                <p class="mt-3 text-3xl font-bold text-rose-950">{{ number_format($postulaciones->total()) }}</p>
                <p class="mt-1 text-xs text-rose-900">Expedientes enviados</p>
            </article>
            <article class="rounded-2xl border border-sky-200 bg-sky-50/70 p-5 shadow-sm">
                <p class="text-sm font-medium text-sky-900">Notificaciones nuevas</p>
                <p class="mt-3 text-3xl font-bold text-sky-950">{{ number_format($unreadNotificationsCount) }}</p>
                <a href="{{ route('notifications.index') }}" class="mt-1 inline-block text-xs font-semibold text-sky-900 hover:underline">
                    Consultar notificaciones
                </a>
            </article>
        </div>

    <!-- SECCIÓN: Matriz Visual de Promoción y Compactación -->
    <section class="space-y-6 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="teaching-positions-heading">
        
        <!-- Encabezado de la Sección -->
        <div class="border-b border-gray-100 pb-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold uppercase tracking-wider text-rose-900 bg-rose-100/80 px-2.5 py-1 rounded-md">
                        Fundamento Convocatoria
                    </span>
                    <span class="text-xs font-semibold uppercase tracking-wider text-amber-800 bg-amber-100 px-2.5 py-1 rounded-md">
                        Matriz de Categorías
                    </span>
                </div>
                <h2 id="teaching-positions-heading" class="mt-2 text-xl font-bold text-gray-950">
                    Promoción Docente con Plazas de Jornada
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">
                    Movimientos verticales (<strong class="text-rose-900">Promoción</strong>) e individuales horizontales (<strong class="text-sky-900">Compactación</strong>).
                </p>
            </div>

            <div class="bg-amber-50 border border-amber-200/80 rounded-xl p-3 flex items-start gap-2.5 max-w-md">
                <span class="text-amber-600 text-base">⚠️</span>
                <p class="text-xs text-amber-900 leading-snug font-medium">
                    <strong class="font-bold">Regla institucional:</strong> Solo se podrá promover al nivel inmediato superior de la jornada correspondiente.
                </p>
            </div>
        </div>

        <!-- Matriz Alineada por Niveles -->
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="hidden grid-cols-4 items-center gap-4 bg-slate-950 px-5 py-4 text-center text-xs font-semibold uppercase tracking-wider text-white md:grid">
                <div class="font-mono text-slate-400">Nivel / Rango</div>
                <div class="text-sky-300">
                    ½ Tiempo
                    <span class="mt-1 block text-[10px] font-normal lowercase tracking-normal text-slate-400">(Claves E36xx)</span>
                </div>
                <div class="text-sky-300">
                    ¾ Tiempo
                    <span class="mt-1 block text-[10px] font-normal lowercase tracking-normal text-slate-400">(Claves E37xx)</span>
                </div>
                <div class="text-emerald-300">
                    Tiempo Completo
                    <span class="mt-1 block text-[10px] font-normal lowercase tracking-normal text-slate-400">(Claves E38xx)</span>
                </div>
            </div>

            <div class="divide-y divide-slate-100 text-sm">
                <div class="grid grid-cols-2 items-center gap-3 bg-white p-4 transition-colors hover:bg-rose-50/30 md:grid-cols-4 md:gap-4 md:px-5 md:py-3.5">
                    <span class="col-span-2 inline-flex w-fit rounded-lg bg-slate-100 px-3 py-1.5 font-mono text-xs font-bold text-slate-600 md:col-span-1 md:justify-self-center">Nivel 17</span>
                    <div class="flex flex-col items-center justify-center gap-1 md:flex-row md:gap-1.5">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 md:hidden">½ Tiempo</span>
                        <x-plaza-code code="E3617" :status="$plazaStatuses['E3617'] ?? 'none'" class="font-bold" />
                        <span class="rounded bg-slate-100 px-1.5 py-0.5 text-[10px] font-normal text-slate-500">+6a9 hrs</span>
                    </div>
                    <div class="flex flex-col items-center justify-center gap-1 md:flex-row md:gap-1.5">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 md:hidden">¾ Tiempo</span>
                        <x-plaza-code code="E3717" :status="$plazaStatuses['E3717'] ?? 'none'" class="font-bold" />
                        <span class="rounded bg-slate-100 px-1.5 py-0.5 text-[10px] font-normal text-slate-500">+6a9 hrs</span>
                    </div>
                    <div class="col-span-2 flex flex-col items-center justify-center gap-1 md:col-span-1 md:flex-row">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-emerald-700 md:hidden">Tiempo Completo</span>
                        <x-plaza-code code="E3817" :status="$plazaStatuses['E3817'] ?? 'none'" class="w-full font-bold md:w-auto" />
                    </div>
                </div>

                <div class="grid grid-cols-2 items-center gap-3 bg-white p-4 transition-colors hover:bg-rose-50/30 md:grid-cols-4 md:gap-4 md:px-5 md:py-3.5">
                    <span class="col-span-2 inline-flex w-fit rounded-lg bg-slate-100 px-3 py-1.5 font-mono text-xs font-bold text-slate-600 md:col-span-1 md:justify-self-center">Nivel 15</span>
                    <div class="flex flex-col items-center justify-center gap-1 md:flex-row">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 md:hidden">½ Tiempo</span>
                        <x-plaza-code code="E3615" :status="$plazaStatuses['E3615'] ?? 'none'" />
                    </div>
                    <div class="flex flex-col items-center justify-center gap-1 md:flex-row">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 md:hidden">¾ Tiempo</span>
                        <x-plaza-code code="E3715" :status="$plazaStatuses['E3715'] ?? 'none'" />
                    </div>
                    <div class="col-span-2 flex flex-col items-center justify-center gap-1 md:col-span-1 md:flex-row">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-emerald-700 md:hidden">Tiempo Completo</span>
                        <x-plaza-code code="E3815" :status="$plazaStatuses['E3815'] ?? 'none'" class="w-full font-bold md:w-auto" />
                    </div>
                </div>

                <div class="grid grid-cols-2 items-center gap-3 bg-slate-50/60 p-4 transition-colors hover:bg-rose-50/30 md:grid-cols-4 md:gap-4 md:px-5 md:py-3.5">
                    <span class="col-span-2 inline-flex w-fit rounded-lg bg-slate-100 px-3 py-1.5 font-mono text-xs font-bold text-slate-600 md:col-span-1 md:justify-self-center">Nivel 13</span>
                    <div class="flex flex-col items-center justify-center gap-1 md:flex-row">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 md:hidden">½ Tiempo</span>
                        <x-plaza-code code="E3613" :status="$plazaStatuses['E3613'] ?? 'none'" />
                    </div>
                    <div class="flex flex-col items-center justify-center gap-1 md:flex-row">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 md:hidden">¾ Tiempo</span>
                        <x-plaza-code code="E3713" :status="$plazaStatuses['E3713'] ?? 'none'" />
                    </div>
                    <div class="col-span-2 flex flex-col items-center justify-center gap-1 md:col-span-1 md:flex-row">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-emerald-700 md:hidden">Tiempo Completo</span>
                        <x-plaza-code code="E3813" :status="$plazaStatuses['E3813'] ?? 'none'" class="w-full font-bold md:w-auto" />
                    </div>
                </div>

                <div class="grid grid-cols-2 items-center gap-3 bg-white p-4 transition-colors hover:bg-rose-50/30 md:grid-cols-4 md:gap-4 md:px-5 md:py-3.5">
                    <span class="col-span-2 inline-flex w-fit rounded-lg bg-slate-100 px-3 py-1.5 font-mono text-xs font-bold text-slate-600 md:col-span-1 md:justify-self-center">Nivel 11</span>
                    <div class="flex flex-col items-center justify-center gap-1 md:flex-row">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 md:hidden">½ Tiempo</span>
                        <x-plaza-code code="E3611" :status="$plazaStatuses['E3611'] ?? 'none'" />
                    </div>
                    <div class="flex flex-col items-center justify-center gap-1 md:flex-row">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 md:hidden">¾ Tiempo</span>
                        <x-plaza-code code="E3711" :status="$plazaStatuses['E3711'] ?? 'none'" />
                    </div>
                    <div class="col-span-2 flex flex-col items-center justify-center gap-1 md:col-span-1 md:flex-row">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-emerald-700 md:hidden">Tiempo Completo</span>
                        <x-plaza-code code="E3811" :status="$plazaStatuses['E3811'] ?? 'none'" class="w-full font-bold md:w-auto" />
                    </div>
                </div>

                <div class="grid grid-cols-2 items-center gap-3 bg-white p-4 transition-colors hover:bg-rose-50/30 md:grid-cols-4 md:gap-4 md:px-5 md:py-3.5">
                    <span class="col-span-2 inline-flex w-fit rounded-lg bg-slate-100 px-3 py-1.5 font-mono text-xs font-bold text-slate-600 md:col-span-1 md:justify-self-center">Nivel 09</span>
                    <div class="flex flex-col items-center justify-center gap-1 md:flex-row">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 md:hidden">½ Tiempo</span>
                        <x-plaza-code code="E3609" :status="$plazaStatuses['E3609'] ?? 'none'" />
                    </div>
                    <div class="flex flex-col items-center justify-center gap-1 md:flex-row">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 md:hidden">¾ Tiempo</span>
                        <x-plaza-code code="E3709" :status="$plazaStatuses['E3709'] ?? 'none'" />
                    </div>
                    <div class="col-span-2 flex flex-col items-center justify-center gap-1 md:col-span-1 md:flex-row">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-emerald-700 md:hidden">Tiempo Completo</span>
                        <x-plaza-code code="E3809" :status="$plazaStatuses['E3809'] ?? 'none'" class="w-full font-bold md:w-auto" />
                    </div>
                </div>

                <div class="grid grid-cols-2 items-center gap-3 bg-slate-50/60 p-4 transition-colors hover:bg-rose-50/30 md:grid-cols-4 md:gap-4 md:px-5 md:py-3.5">
                    <span class="col-span-2 inline-flex w-fit rounded-lg bg-slate-100 px-3 py-1.5 font-mono text-xs font-bold text-slate-600 md:col-span-1 md:justify-self-center">Nivel 07</span>
                    <div class="flex flex-col items-center justify-center gap-1 md:flex-row">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 md:hidden">½ Tiempo</span>
                        <x-plaza-code code="E3607" :status="$plazaStatuses['E3607'] ?? 'none'" />
                    </div>
                    <div class="flex flex-col items-center justify-center gap-1 md:flex-row">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 md:hidden">¾ Tiempo</span>
                        <x-plaza-code code="E3707" :status="$plazaStatuses['E3707'] ?? 'none'" />
                    </div>
                    <div class="col-span-2 flex flex-col items-center justify-center gap-1 md:col-span-1 md:flex-row">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-emerald-700 md:hidden">Tiempo Completo</span>
                        <x-plaza-code code="E3807" :status="$plazaStatuses['E3807'] ?? 'none'" class="w-full font-bold md:w-auto" />
                    </div>
                </div>
            </div>
        </div>

        <ul aria-label="Estados de las plazas" class="flex flex-wrap gap-x-4 gap-y-2 text-xs">
            <li class="inline-flex items-center gap-2">
                <span aria-hidden="true" class="size-3 rounded-sm border border-violet-300 bg-violet-100"></span>
                Mi plaza
            </li>
            <li class="inline-flex items-center gap-2">
                <span aria-hidden="true" class="size-3 rounded-sm border border-amber-300 bg-amber-100"></span>
                Próxima disponibilidad
            </li>
            <li class="inline-flex items-center gap-2">
                <span aria-hidden="true" class="size-3 rounded-sm border border-rose-300 bg-rose-100"></span>
                Ocupada
            </li>
            <li class="inline-flex items-center gap-2">
                <span aria-hidden="true" class="size-3 rounded-sm border border-emerald-300 bg-emerald-100"></span>
                Disponible en convocatoria
            </li>
            <li class="inline-flex items-center gap-2">
                <span aria-hidden="true" class="size-3 rounded-sm border border-slate-200 bg-slate-50"></span>
                Sin asignación ni convocatoria
            </li>
        </ul>

        <!-- Ficha Descriptiva de Reglas -->
        <div class="grid grid-cols-1 gap-4 pt-2 text-sm lg:grid-cols-2">
            <div class="flex min-h-28 items-start gap-4 rounded-2xl border border-rose-100 bg-gradient-to-br from-rose-50 via-white to-rose-50/50 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:p-5">
                <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-rose-900 text-lg text-white shadow-sm ring-4 ring-rose-100">⬆️</span>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wide text-rose-950">Promoción (Vertical)</h4>
                    <p class="mt-1 leading-relaxed text-gray-600">
                        Movimiento ascendente entre categorías dentro de la misma columna (mismo tipo de jornada).
                    </p>
                </div>
            </div>

            <div class="flex min-h-28 items-start gap-4 rounded-2xl border border-sky-100 bg-gradient-to-br from-sky-50 via-white to-sky-50/50 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:p-5">
                <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-sky-900 text-lg text-white shadow-sm ring-4 ring-sky-100">⬅️</span>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wide text-sky-950">Compactación (Horizontal)</h4>
                    <p class="mt-1 leading-relaxed text-gray-600">
                        Movimiento hacia la siguiente categoría en el mismo nivel horizontal entre distintas jornadas.
                    </p>
                </div>
            </div>
        </div>

    </section>

        <section class="space-y-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="applications-heading">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 id="applications-heading" class="text-lg font-bold text-gray-950">Seguimiento de postulaciones</h2>
                    <p class="mt-1 text-sm text-gray-600">Consulta el estado de tus expedientes enviados.</p>
                </div>
                <a href="{{ route('postulaciones.index') }}" class="text-sm font-semibold text-rose-900 hover:text-rose-700 hover:underline">
                    Ver todas mis postulaciones
                </a>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse ($postulaciones as $postulacion)
                    <article class="flex flex-col gap-4 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0">
                            <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-900">
                                {{ $postulacion->convocatoria?->tipo_personal ?? 'Tipo no disponible' }}
                            </span>
                            <h3 class="mt-2 font-semibold text-gray-950">{{ $postulacion->convocatoria?->titulo ?? 'Convocatoria no disponible' }}</h3>
                            <div class="mt-2 flex flex-wrap items-center gap-2 text-sm text-gray-600">
                                <span>Enviada el {{ $postulacion->created_at->format('d/m/Y') }}</span>
                                <span aria-hidden="true" class="text-gray-300">·</span>
                                <span @class([
                                    'rounded-full px-2.5 py-1 text-xs font-semibold',
                                    'bg-amber-100 text-amber-900' => $postulacion->estatus === 'pendiente',
                                    'bg-emerald-100 text-emerald-900' => $postulacion->estatus === 'aprobada',
                                    'bg-rose-100 text-rose-900' => $postulacion->estatus === 'rechazada',
                                    'bg-gray-100 text-gray-700' => ! in_array($postulacion->estatus, ['pendiente', 'aprobada', 'rechazada'], true),
                                ])>{{ ucfirst($postulacion->estatus) }}</span>
                            </div>
                        </div>
                        <a href="{{ route('postulaciones.show', $postulacion) }}" class="shrink-0 rounded-lg border border-rose-200 px-3 py-2 text-center text-sm font-semibold text-rose-900 transition hover:bg-rose-50">
                            Ver expediente enviado
                        </a>
                    </article>
                @empty
                    <div class="rounded-xl bg-gray-50 px-4 py-8 text-center">
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