@extends('layouts.app')

@section('title', 'Panel del Aspirante - Sistema de Convocatorias ITM')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Encabezado de Bienvenida -->
    <div class="bg-rose-50/80 border border-rose-100 rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-rose-950">
                Hola, {{ Auth::user()->name ?? 'Carlos Eduardo Mendoza García' }}
            </h1>
            <p class="text-sm text-rose-800 mt-1">
                Consulta las convocatorias, presenta tu postulación y revisa las notificaciones de tus trámites.
            </p>
        </div>
        <a href="{{ route('home') }}#convocatorias" class="inline-flex items-center px-5 py-2.5 bg-rose-900 hover:bg-rose-950 text-white font-medium text-sm rounded-xl transition-all duration-200 shadow-sm">
            Ver convocatorias
        </a>
    </div>

    <!-- Indicadores Rápidos -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <span class="text-sm font-medium text-gray-500">Mis postulaciones</span>
            <span class="text-4xl font-extrabold text-rose-900 mt-2">
                {{ $postulacionesCount ?? 0 }}
            </span>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <span class="text-sm font-medium text-gray-500">Notificaciones nuevas</span>
            <span class="text-4xl font-extrabold text-rose-900 mt-2">
                {{ $notificacionesCount ?? 0 }}
            </span>
        </div>
    </div>

    <!-- SECCIÓN: Matriz Visual de Promoción y Compactación -->
    <div class="bg-white border border-gray-100 rounded-2xl p-6 md:p-8 shadow-sm space-y-6">
        
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
                <h2 class="text-xl font-bold text-gray-900 mt-2">
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
        <div class="overflow-x-auto">
            <div class="min-w-[650px] border border-gray-100 rounded-xl overflow-hidden bg-slate-50/50">
                
                <!-- Cabecera de la Tabla -->
                <div class="grid grid-cols-4 bg-slate-900 text-white text-xs font-semibold uppercase tracking-wider p-3.5 text-center items-center">
                    <div class="text-slate-400 font-mono">Nivel / Rango</div>
                    <div class="text-sky-300">
                        ½ Tiempo 
                        <span class="block text-[10px] text-slate-400 font-normal lowercase">(Claves E36xx)</span>
                    </div>
                    <div class="text-sky-300">
                        ¾ Tiempo 
                        <span class="block text-[10px] text-slate-400 font-normal lowercase">(Claves E37xx)</span>
                    </div>
                    <div class="text-emerald-300">
                        Tiempo Completo 
                        <span class="block text-[10px] text-slate-400 font-normal lowercase">(Claves E38xx)</span>
                    </div>
                </div>

                <!-- Filas por Nivel (De Nivel 17 a 07) -->
                <div class="divide-y divide-gray-200/70 text-xs">
                    
                    <!-- Nivel 17 -->
                    <div class="grid grid-cols-4 p-2.5 items-center text-center bg-white hover:bg-rose-50/30 transition-colors">
                        <span class="font-bold text-gray-500 font-mono bg-slate-100 py-1 px-2 rounded-md mx-auto">Nivel 17</span>
                        <div class="flex items-center justify-center gap-1 font-bold text-slate-800 font-mono">
                            E3617
                            <span class="text-[9px] font-sans font-normal text-slate-500 bg-slate-100 px-1 rounded">+6a9 hrs</span>
                        </div>
                        <div class="flex items-center justify-center gap-1 font-bold text-slate-800 font-mono">
                            E3717
                            <span class="text-[9px] font-sans font-normal text-slate-500 bg-slate-100 px-1 rounded">+6a9 hrs</span>
                        </div>
                        <div class="font-bold text-emerald-800 font-mono bg-emerald-50 py-1 rounded border border-emerald-100">
                            E3817
                        </div>
                    </div>

                    <!-- Nivel 15 -->
                    <div class="grid grid-cols-4 p-2.5 items-center text-center bg-white hover:bg-rose-50/30 transition-colors">
                        <span class="font-bold text-gray-500 font-mono bg-slate-100 py-1 px-2 rounded-md mx-auto">Nivel 15</span>
                        <div class="font-mono text-slate-700">E3615</div>
                        <div class="font-mono text-slate-700">E3715</div>
                        <div class="font-bold text-emerald-800 font-mono bg-emerald-50 py-1 rounded border border-emerald-100">E3815</div>
                    </div>

                    <!-- Nivel 13 -->
                    <div class="grid grid-cols-4 p-2.5 items-center text-center bg-white hover:bg-rose-50/30 transition-colors">
                        <span class="font-bold text-gray-500 font-mono bg-slate-100 py-1 px-2 rounded-md mx-auto">Nivel 13</span>
                        <div class="font-mono text-slate-700">E3613</div>
                        <div class="font-mono text-slate-700">E3713</div>
                        <div class="font-bold text-emerald-800 font-mono bg-emerald-50 py-1 rounded border border-emerald-100">E3813</div>
                    </div>

                    <!-- Nivel 11 -->
                    <div class="grid grid-cols-4 p-2.5 items-center text-center bg-white hover:bg-rose-50/30 transition-colors">
                        <span class="font-bold text-gray-500 font-mono bg-slate-100 py-1 px-2 rounded-md mx-auto">Nivel 11</span>
                        <div class="font-mono text-slate-700">E3611</div>
                        <div class="font-mono text-slate-700">E3711</div>
                        <div class="font-bold text-emerald-800 font-mono bg-emerald-50 py-1 rounded border border-emerald-100">E3811</div>
                    </div>

                    <!-- Nivel 09 -->
                    <div class="grid grid-cols-4 p-2.5 items-center text-center bg-white hover:bg-rose-50/30 transition-colors">
                        <span class="font-bold text-gray-500 font-mono bg-slate-100 py-1 px-2 rounded-md mx-auto">Nivel 09</span>
                        <div class="font-mono text-slate-700">E3609</div>
                        <div class="font-mono text-slate-700">E3709</div>
                        <div class="font-bold text-emerald-800 font-mono bg-emerald-50 py-1 rounded border border-emerald-100">E3809</div>
                    </div>

                    <!-- Nivel 07 -->
                    <div class="grid grid-cols-4 p-2.5 items-center text-center bg-white hover:bg-rose-50/30 transition-colors">
                        <span class="font-bold text-gray-500 font-mono bg-slate-100 py-1 px-2 rounded-md mx-auto">Nivel 07</span>
                        <div class="font-mono text-slate-700">E3607</div>
                        <div class="font-mono text-slate-700">E3707</div>
                        <div class="font-bold text-emerald-800 font-mono bg-emerald-50 py-1 rounded border border-emerald-100">E3807</div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Ficha Descriptiva de Reglas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs pt-2">
            <div class="flex items-start gap-3 bg-rose-50/70 p-3.5 rounded-xl border border-rose-100">
                <span class="bg-rose-900 text-white p-1.5 rounded-lg text-sm leading-none">⬆️</span>
                <div>
                    <h4 class="font-bold text-rose-950 uppercase text-[11px] tracking-wide">Promoción (Vertical)</h4>
                    <p class="text-gray-600 mt-0.5 leading-snug">
                        Movimiento ascendente entre categorías dentro de la misma columna (mismo tipo de jornada).
                    </p>
                </div>
            </div>

            <div class="flex items-start gap-3 bg-sky-50/70 p-3.5 rounded-xl border border-sky-100">
                <span class="bg-sky-900 text-white p-1.5 rounded-lg text-sm leading-none">⬅️</span>
                <div>
                    <h4 class="font-bold text-sky-950 uppercase text-[11px] tracking-wide">Compactación (Horizontal)</h4>
                    <p class="text-gray-600 mt-0.5 leading-snug">
                        Movimiento hacia la siguiente categoría en el mismo nivel horizontal entre distintas jornadas.
                    </p>
                </div>
            </div>
        </div>

    </div>

    <!-- Seguimiento de Postulaciones -->
    <div class="bg-white border border-gray-100 rounded-2xl p-6 md:p-8 shadow-sm space-y-6">
        <h2 class="text-lg font-bold text-gray-900">Seguimiento de postulaciones</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-500">
                <thead class="bg-gray-50 text-gray-700 uppercase tracking-wider font-semibold">
                    <tr>
                        <th class="py-3 px-4">Convocatoria</th>
                        <th class="py-3 px-4">Tipo</th>
                        <th class="py-3 px-4">Fecha</th>
                        <th class="py-3 px-4">Estatus</th>
                        <th class="py-3 px-4 text-right">Detalle</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($postulaciones ?? [] as $postulacion)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3.5 px-4 font-medium text-gray-900">{{ $postulacion->convocatoria->titulo ?? 'N/A' }}</td>
                            <td class="py-3.5 px-4">{{ $postulacion->convocatoria->tipo_personal ?? 'N/A' }}</td>
                            <td class="py-3.5 px-4">{{ $postulacion->created_at->format('d/m/Y') }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                                    {{ $postulacion->estatus ?? 'En revisión' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="#" class="text-rose-900 hover:text-rose-950 font-medium hover:underline">Ver expediente</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <tsd colspan="5" class="py-12 text-center text-gray-400">
                                Aún no tienes postulaciones registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection