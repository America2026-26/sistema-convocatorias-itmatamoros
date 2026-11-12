@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50/50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Pestañas de Navegación Secundaria -->
        <div class="flex space-x-3 mb-6 border-b border-slate-200 pb-4">
            <a href="#" class="px-4 py-2 text-xs font-bold uppercase tracking-wider bg-indigo-600 text-white rounded-lg shadow-sm">
                Fundamento Convocatoria
            </a>
            <a href="#" class="px-4 py-2 text-xs font-bold uppercase tracking-wider bg-white text-slate-700 border border-slate-200 rounded-lg hover:bg-slate-100 transition">
                Matriz de Categorías
            </a>
        </div>

        <!-- Cabecera y Alerta -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 items-start">
            <div class="lg:col-span-2">
                <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full mb-2">
                    Convocatoria Activa
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Promoción Docente con Plazas de Jornada
                </h1>
                <p class="text-sm text-slate-600 mt-1">
                    Movimientos verticales (<span class="font-semibold text-slate-900">Promoción</span>) e individuales horizontales (<span class="font-semibold text-slate-900">Compactación</span>).
                </p>
            </div>

            <!-- Alerta Institucional -->
            <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-xl shadow-xs">
                <div class="flex items-start space-x-3">
                    <span class="text-amber-500 text-lg">⚠️</span>
                    <div class="text-xs sm:text-sm text-amber-900">
                        <span class="font-bold block mb-0.5">Regla institucional:</span>
                        Solo se podrá promover al nivel inmediato superior de la jornada correspondiente.
                    </div>
                </div>
            </div>
        </div>

        <!-- Título de Sección -->
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span>📊</span> Matriz de Categorías por Nivel y Jornada
            </h2>
            <span class="text-xs text-slate-500 font-medium">Claves E36xx, E37xx y E38xx</span>
        </div>

        <!-- Grid de Tarjetas por Nivel -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Nivel 17 -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <h3 class="font-extrabold text-slate-900 text-lg">Nivel 17</h3>
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-xs font-bold rounded-md">Máximo Rango</span>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center py-1.5 px-3 bg-slate-50 rounded-lg">
                            <span class="text-xs font-semibold text-slate-500">½ Tiempo (e36xx)</span>
                            <span class="font-bold text-slate-800">E3617 <span class="text-xs font-normal text-slate-400">+6a9 hrs</span></span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 px-3 bg-slate-50 rounded-lg">
                            <span class="text-xs font-semibold text-slate-500">¾ Tiempo (e37xx)</span>
                            <span class="font-bold text-slate-800">E3717 <span class="text-xs font-normal text-slate-400">+6a9 hrs</span></span>
                        </div>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Tiempo Completo</span>
                    <span class="px-4 py-1.5 bg-emerald-50 text-emerald-700 font-extrabold text-sm rounded-lg border border-emerald-200">
                        E3817
                    </span>
                </div>
            </div>

            <!-- Nivel 15 -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <h3 class="font-extrabold text-slate-900 text-lg">Nivel 15</h3>
                        <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-md">Ascenso</span>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center py-1.5 px-3 bg-slate-50 rounded-lg">
                            <span class="text-xs font-semibold text-slate-500">½ Tiempo (e36xx)</span>
                            <span class="font-bold text-slate-800">E3615</span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 px-3 bg-slate-50 rounded-lg">
                            <span class="text-xs font-semibold text-slate-500">¾ Tiempo (e37xx)</span>
                            <span class="font-bold text-slate-800">E3715</span>
                        </div>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Tiempo Completo</span>
                    <span class="px-4 py-1.5 bg-emerald-50 text-emerald-700 font-extrabold text-sm rounded-lg border border-emerald-200">
                        E3815
                    </span>
                </div>
            </div>

            <!-- Nivel 13 -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <h3 class="font-extrabold text-slate-900 text-lg">Nivel 13</h3>
                        <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-md">Ascenso</span>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center py-1.5 px-3 bg-slate-50 rounded-lg">
                            <span class="text-xs font-semibold text-slate-500">½ Tiempo (e36xx)</span>
                            <span class="font-bold text-slate-800">E3613</span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 px-3 bg-slate-50 rounded-lg">
                            <span class="text-xs font-semibold text-slate-500">¾ Tiempo (e37xx)</span>
                            <span class="font-bold text-slate-800">E3713</span>
                        </div>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Tiempo Completo</span>
                    <span class="px-4 py-1.5 bg-emerald-50 text-emerald-700 font-extrabold text-sm rounded-lg border border-emerald-200">
                        E3813
                    </span>
                </div>
            </div>

            <!-- Nivel 11 -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <h3 class="font-extrabold text-slate-900 text-lg">Nivel 11</h3>
                        <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-md">Ascenso</span>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center py-1.5 px-3 bg-slate-50 rounded-lg">
                            <span class="text-xs font-semibold text-slate-500">½ Tiempo (e36xx)</span>
                            <span class="font-bold text-slate-800">E3611</span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 px-3 bg-slate-50 rounded-lg">
                            <span class="text-xs font-semibold text-slate-500">¾ Tiempo (e37xx)</span>
                            <span class="font-bold text-slate-800">E3711</span>
                        </div>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Tiempo Completo</span>
                    <span class="px-4 py-1.5 bg-emerald-50 text-emerald-700 font-extrabold text-sm rounded-lg border border-emerald-200">
                        E3811
                    </span>
                </div>
            </div>

            <!-- Nivel 09 -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <h3 class="font-extrabold text-slate-900 text-lg">Nivel 09</h3>
                        <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-md">Base</span>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center py-1.5 px-3 bg-slate-50 rounded-lg">
                            <span class="text-xs font-semibold text-slate-500">½ Tiempo (e36xx)</span>
                            <span class="font-bold text-slate-800">E3609</span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 px-3 bg-slate-50 rounded-lg">
                            <span class="text-xs font-semibold text-slate-500">¾ Tiempo (e37xx)</span>
                            <span class="font-bold text-slate-800">E3709</span>
                        </div>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Tiempo Completo</span>
                    <span class="px-4 py-1.5 bg-emerald-50 text-emerald-700 font-extrabold text-sm rounded-lg border border-emerald-200">
                        E3809
                    </span>
                </div>
            </div>

            <!-- Nivel 07 -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <h3 class="font-extrabold text-slate-900 text-lg">Nivel 07</h3>
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-xs font-bold rounded-md">Inicial</span>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center py-1.5 px-3 bg-slate-50 rounded-lg">
                            <span class="text-xs font-semibold text-slate-500">½ Tiempo (e36xx)</span>
                            <span class="font-bold text-slate-800">E3607</span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 px-3 bg-slate-50 rounded-lg">
                            <span class="text-xs font-semibold text-slate-500">¾ Tiempo (e37xx)</span>
                            <span class="font-bold text-slate-800">E3707</span>
                        </div>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Tiempo Completo</span>
                    <span class="px-4 py-1.5 bg-emerald-50 text-emerald-700 font-extrabold text-sm rounded-lg border border-emerald-200">
                        E3807
                    </span>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection