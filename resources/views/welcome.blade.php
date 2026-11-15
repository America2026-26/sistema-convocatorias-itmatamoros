@extends('layouts.app')

@section('title', 'Convocatorias Vigentes - Sistema de Convocatorias ITM')

@section('content')

    <section
        class="bg-gradient-to-r from-rose-950 via-rose-900 to-red-900 text-white py-12 px-4 sm:px-6 lg:px-8 shadow-inner">
        <div class="max-w-7xl mx-auto text-center space-y-4">
            <span
                class="bg-rose-800 text-rose-100 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider">Módulo
                de Oposiciones y Convocatorias</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold">Postulación y Registro de Expedientes Digitales</h2>
            <p class="max-w-2xl mx-auto text-rose-100 text-sm sm:text-base">
                Consulta las plazas docentes y no docentes disponibles, revisa los requisitos e inicia tu proceso de
                postulación digital.
            </p>
        </div>
    </section>

    <section id="convocatorias" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="mb-6 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">Convocatorias Vigentes</h3>
                <p class="mt-1 text-sm text-gray-500">{{ $convocatorias->total() }} convocatoria(s) activa(s)</p>
            </div>
        </div>

        <form action="{{ route('home') }}#convocatorias" method="GET"
            class="mb-8 grid grid-cols-1 items-end gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-[1fr_1fr_auto_auto]">
            <div>
                <label for="filter_tipo_concurso" class="mb-1 block text-sm font-semibold text-gray-700">Categoría</label>
                <select id="filter_tipo_concurso" name="tipo_concurso"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-900">
                    <option value="" @selected($filters['tipo_concurso'] === '')>Todas</option>
                    <option value="Abierta" @selected($filters['tipo_concurso'] === 'Abierta')>Abiertas</option>
                    <option value="Cerrada" @selected($filters['tipo_concurso'] === 'Cerrada')>Cerradas</option>
                </select>
            </div>
            <div>
                <label for="filter_tipo_personal" class="mb-1 block text-sm font-semibold text-gray-700">Tipo de personal</label>
                <select id="filter_tipo_personal" name="tipo_personal"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-900">
                    <option value="" @selected($filters['tipo_personal'] === '')>Todos</option>
                    <option value="Docente" @selected($filters['tipo_personal'] === 'Docente')>Docente</option>
                    <option value="No docente" @selected($filters['tipo_personal'] === 'No docente')>No docente</option>
                </select>
            </div>
            @if ($highlightedPlaza !== null)
                <input type="hidden" name="plaza" value="{{ $highlightedPlaza }}">
            @endif
            <button type="submit"
                class="rounded-lg bg-rose-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-800">
                Aplicar filtros
            </button>
            @if ($filters['tipo_concurso'] !== '' || $filters['tipo_personal'] !== '' || $highlightedPlaza !== null)
                <a href="{{ route('home') }}#convocatorias"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-center text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    Limpiar
                </a>
            @endif
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($convocatorias as $convocatoria)
                @php($postulacionEnviada = $convocatoria->postulaciones->first())
                <div id="convocatoria-{{ $convocatoria->id }}" @class([
                    'flex flex-col justify-between overflow-hidden rounded-xl border shadow-md transition duration-200',
                    'border-emerald-300 bg-emerald-50/70 hover:shadow-lg' => $postulacionEnviada,
                    'border-gray-200 bg-white hover:shadow-lg' => ! $postulacionEnviada,
                    'animate-convocatoria-highlight' => $highlightedPlaza !== null,
                ])>
                    <!-- Cabecera de la tarjeta -->
                    <div class="p-6">
                        <div class="flex justify-between items-start gap-2 mb-3">
                            <span
                                class="text-xs font-semibold uppercase px-2.5 py-1 rounded-md {{ $convocatoria->tipo_personal == 'Docente' ? 'bg-rose-100 text-rose-900' : 'bg-amber-100 text-amber-800' }}">
                                {{ $convocatoria->tipo_personal }} - {{ $convocatoria->tipo_concurso }}
                            </span>
                            <span class="text-xs font-semibold bg-emerald-100 text-emerald-800 px-2.5 py-1 rounded-md">
                                {{ $convocatoria->estatus }}
                            </span>
                        </div>

                        <h4 class="text-xl font-bold text-gray-900 mb-2 leading-snug">
                            {{ $convocatoria->titulo }}
                        </h4>

                        @include('convocatorias._details', ['convocatoria' => $convocatoria, 'compact' => true])
                    </div>

                    <!-- Botón de acción en Guinda -->
                    <div @class([
                        'flex items-center justify-between border-t px-6 py-3',
                        'border-emerald-200 bg-emerald-100/70' => $postulacionEnviada,
                        'border-gray-100 bg-gray-50' => ! $postulacionEnviada,
                    ])>
                        <a href="{{ route('convocatorias.show', $convocatoria) }}"
                            class="text-xs font-semibold text-rose-900 hover:text-rose-700">Ver bases completas &rarr;</a>
                        <a href="{{ $postulacionEnviada ? route('postulaciones.show', $postulacionEnviada) : route('convocatorias.show', $convocatoria) }}"
                            @class([
                                'rounded-lg px-4 py-2 text-xs font-medium text-white shadow transition',
                                'bg-emerald-700 hover:bg-emerald-800' => $postulacionEnviada,
                                'bg-rose-900 hover:bg-rose-800' => ! $postulacionEnviada,
                            ])>
                            {{ $postulacionEnviada ? 'Ver postulación' : 'Ver convocatoria' }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        @if ($convocatorias->isEmpty())
            <p class="mt-6 rounded-xl border border-gray-200 bg-white p-8 text-center text-gray-600">
                No hay convocatorias activas que coincidan con estos filtros.
            </p>
        @endif
        <div class="mt-6">{{ $convocatorias->links() }}</div>
    </section>
@endsection