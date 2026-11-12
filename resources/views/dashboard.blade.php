@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Pestañas de Navegación Secundaria -->
        <div class="flex space-x-3 mb-6 border-b border-slate-200 pb-4">
            <a href="#" class="px-4 py-2 text-xs font-bold uppercase tracking-wider bg-rose-700 text-white rounded-lg shadow-sm">
                Fundamento Convocatoria
            </a>
            <a href="#" class="px-4 py-2 text-xs font-bold uppercase tracking-wider bg-white text-slate-700 border border-slate-200 rounded-lg hover:bg-slate-100 transition">
                Matriz de Categorías
            </a>
        </div>

        <!-- Cabecera y Alerta Institucional -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Promoción Docente con Plazas de Jornada
                </h1>
                <p class="text-sm text-slate-600 mt-1">
                    Movimientos verticales (<span class="font-semibold text-rose-700">Promoción</span>) e individuales horizontales (<span class="font-semibold text-slate-900">Compactación</span>).
                </p>
            </div>

            <!-- Alerta Institucional basada en tu esquema -->
            <div class="bg-amber-50 border border-amber-300 text-amber-900 px-4 py-3 rounded-xl text-xs sm:text-sm flex items-start space-x-3 max-w-md shadow-2xs">
                <span class="text-amber-600 font-bold text-lg leading-none">⚠️</span>
                <div>
                    <span class="font-bold">Regla institucional:</span> Solo se podrá promover al nivel inmediato superior de la jornada correspondiente.
                </div>
            </div>
        </div>

        <!-- Leyenda / Guía Visual -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold">➡️</div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">Compactación (Horizontal)</h4>
                    <p class="text-xs text-slate-500">Mismo nivel, incremento de horas (ej. +6 a 9 hrs).</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-700 flex items-center justify-center font-bold">⬆️</div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">Promoción (Vertical)</h4>
                    <p class="text-xs text-slate-500">Ascenso al nivel inmediato superior de la misma jornada.</p>
                </div>
            </div>
        </div>

        <!-- Tabla Estilo Diagrama (Inspirada en tu esquema de plazas) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            
            <!-- Cabecera de Columnas (Orden exacto de tu infografía: TC -> 3/4 -> 1/2) -->
            <div class="bg-slate-900 text-white px-6 py-4 grid grid-cols-1 sm:grid-cols-4 text-center font-bold text-xs tracking-wider uppercase">
                <div class="text-left">Nivel / Rango</div>
                <div>Tiempo Completo <span class="block text-[10px] text-slate-400 font-normal lowercase">(claves e38xx)</span></div>
                <div>¾ Tpo. <span class="block text-[10px] text-slate-400 font-normal lowercase">(claves e37xx)</span></div>
                <div>½ Tpo. <span class="block text-[10px] text-slate-400 font-normal lowercase">(claves e36xx)</span></div>
            </div>

            <!-- Filas de la Matriz (Niveles del 17 al 07) -->
            <div class="divide-y divide-slate-100 text-sm">
                
                <!-- Nivel 17 -->
                <div class="grid grid-cols-1 sm:grid-cols-4 p-5 items-center hover:bg-slate-50/80 transition">
                    <div class="font-bold text-slate-800 text-left sm:flex items-center gap-2">
                        <span>Nivel 17</span>
                        <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-semibold">Máximo</span>
                    </div>
                    <div class="text-center">
                        <span class="inline-block px-4 py-1.5 bg-emerald-50 text-emerald-700 font-bold rounded-lg border border-emerald-200 shadow-2xs">E3817</span>
                    </div>
                    <div class="text-center text-slate-700 font-semibold">
                        E3717 <span class="text-xs font-normal text-rose-600 block sm:inline">+6 a 9 hrs</span>
                    </div>
                    <div class="text-center text-slate-700 font-semibold">
                        E3617 <span class="text-xs font-normal text-rose-600 block sm:inline">+6 a 9 hrs</span>
                    </div>
                </div>

                <!-- Nivel 15 -->
                <div class="grid grid-cols-1 sm:grid-cols-4 p-5 items-center hover:bg-slate-50/80 transition">
                    <div class="font-bold text-slate-800 text-left">Nivel 15</div>
                    <div class="text-center">
                        <span class="inline-block px-4 py-1.5 bg-emerald-50 text-emerald-700 font-bold rounded-lg border border-emerald-200 shadow-2xs">E3815</span>
                    </div>
                    <div class="text-center text-slate-700 font-semibold">E3715</div>
                    <div class="text-center text-slate-700 font-semibold">E3615</div>
                </div>

                <!-- Nivel 13 -->
                <div class="grid grid-cols-1 sm:grid-cols-4 p-5 items-center hover:bg-slate-50/80 transition">
                    <div class="font-bold text-slate-800 text-left">Nivel 13</div>
                    <div class="text-center">
                        <span class="inline-block px-4 py-1.5 bg-emerald-50 text-emerald-700 font-bold rounded-lg border border-emerald-200 shadow-2xs">E3813</span>
                    </div>
                    <div class="text-center text-slate-700 font-semibold">E3713</div>
                    <div class="text-center text-slate-700 font-semibold">E3613</div>
                </div>

                <!-- Nivel 11 -->
                <div class="grid grid-cols-1 sm:grid-cols-4 p-5 items-center hover:bg-slate-50/80 transition">
                    <div class="font-bold text-slate-800 text-left">Nivel 11</div>
                    <div class="text-center">
                        <span class="inline-block px-4 py-1.5 bg-emerald-50 text-emerald-700 font-bold rounded-lg border border-emerald-200 shadow-2xs">E3811</span>
                    </div>
                    <div class="text-center text-slate-700 font-semibold">E3711</div>
                    <div class="text-center text-slate-700 font-semibold">E3611</div>
                </div>

                <!-- Nivel 09 -->
                <div class="grid grid-cols-1 sm:grid-cols-4 p-5 items-center hover:bg-slate-50/80 transition">
                    <div class="font-bold text-slate-800 text-left">Nivel 09</div>
                    <div class="text-center">
                        <span class="inline-block px-4 py-1.5 bg-emerald-50 text-emerald-700 font-bold rounded-lg border border-emerald-200 shadow-2xs">E3809</span>
                    </div>
                    <div class="text-center text-slate-700 font-semibold">E3709</div>
                    <div class="text-center text-slate-700 font-semibold">E3609</div>
                </div>

                <!-- Nivel 07 -->
                <div class="grid grid-cols-1 sm:grid-cols-4 p-5 items-center hover:bg-slate-50/80 transition">
                    <div class="font-bold text-slate-800 text-left sm:flex items-center gap-2">
                        <span>Nivel 07</span>
                        <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-semibold">Base</span>
                    </div>
                    <div class="text-center">
                        <span class="inline-block px-4 py-1.5 bg-emerald-50 text-emerald-700 font-bold rounded-lg border border-emerald-200 shadow-2xs">E3807</span>
                    </div>
                    <div class="text-center text-slate-700 font-semibold">E3707</div>
                    <div class="text-center text-slate-700 font-semibold">E3607</div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection