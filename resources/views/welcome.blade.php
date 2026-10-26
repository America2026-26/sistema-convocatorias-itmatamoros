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
            <button type="submit"
                class="rounded-lg bg-rose-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-800">
                Aplicar filtros
            </button>
            @if ($filters['tipo_concurso'] !== '' || $filters['tipo_personal'] !== '')
                <a href="{{ route('home') }}#convocatorias"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-center text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    Limpiar
                </a>
            @endif
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($convocatorias as $convocatoria)
                <div
                    class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition duration-200 flex flex-col justify-between">
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

                        <!-- Detalles clave -->
                        <div
                            class="grid grid-cols-2 gap-4 text-xs text-gray-600 my-4 bg-rose-50/50 p-3 rounded-lg border border-rose-100">
                            <div>
                                <span class="block font-semibold text-gray-500">Categoría / Nivel:</span>
                                <span class="text-gray-800 font-medium">{{ $convocatoria->categoria_nivel }}</span>
                            </div>
                            <div>
                                <span class="block font-semibold text-gray-500">Plazas Disponibles:</span>
                                <span class="text-gray-800 font-medium">{{ $convocatoria->plaza_disponible }}</span>
                            </div>
                            <div>
                                <span class="block font-semibold text-gray-500">Jornada / Horario:</span>
                                <span class="text-gray-800 font-medium">{{ $convocatoria->jornada_horario }}</span>
                            </div>
                            <div>
                                <span class="block font-semibold text-gray-500">Remuneración Salarial:</span>
                                <span class="text-emerald-700 font-bold">${{ number_format($convocatoria->salario, 2) }}
                                    MXN</span>
                            </div>
                        </div>

                        <!-- Fechas vigencia -->
                        <div class="text-xs text-gray-500 flex items-center justify-between mb-4">
                            <span><strong>Apertura:</strong>
                                {{ \Carbon\Carbon::parse($convocatoria->fecha_inicio)->format('d/m/Y') }}</span>
                            <span><strong>Cierre:</strong>
                                {{ \Carbon\Carbon::parse($convocatoria->fecha_fin)->format('d/m/Y') }}</span>
                        </div>

                        <!-- Requisitos específicos -->
                        <div class="border-t border-gray-100 pt-3">
                            <p class="text-xs text-gray-600 line-clamp-2">
                                <strong class="text-gray-700">Requisitos:</strong>
                                {{ $convocatoria->requisitos_especificos }}
                            </p>
                        </div>
                    </div>

                    <!-- Botón de acción en Guinda -->
                    <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 flex justify-between items-center">
                        <a href="{{ route('convocatorias.show', $convocatoria) }}"
                            class="text-xs font-semibold text-rose-900 hover:text-rose-700">Ver bases completas &rarr;</a>
                        <a href="{{ route('convocatorias.show', $convocatoria) }}"
                            class="bg-rose-900 hover:bg-rose-800 text-white font-medium text-xs px-4 py-2 rounded-lg shadow transition">
                            Ver convocatoria
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