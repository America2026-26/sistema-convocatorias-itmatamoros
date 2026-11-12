@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50">
    
    <!-- Pestañas de Navegación Secundaria (Fundamento y Matriz) -->
    <div class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex space-x-3 py-3">
                <a href="#" class="px-4 py-2 text-xs font-bold uppercase tracking-wider bg-rose-700 text-white rounded-lg shadow-sm">
                    Fundamento Convocatoria
                </a>
                <a href="#" class="px-4 py-2 text-xs font-bold uppercase tracking-wider bg-amber-200 text-amber-900 rounded-lg hover:bg-amber-300 transition">
                    Matriz de Categorías
                </a>
            </div>
        </div>
    </div>

    <!-- Contenido del Dashboard -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Cabecera de la Sección y Alerta Institucional -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Promoción Docente con Plazas de Jornada
                </h1>
                <p class="text-sm text-slate-600 mt-1">
                    Movimientos verticales (<span class="font-semibold text-slate-900">Promoción</span>) e individuales horizontales (<span class="font-semibold text-slate-900">Compactación</span>).
                </p>
            </div>

            <!-- Alerta Institucional -->
            <div class="bg-amber-50/80 border border-amber-200 text-amber-900 px-4 py-3 rounded-xl text-xs sm:text-sm flex items-start space-x-3 max-w-md shadow-2xs">
                <span class="text-amber-500 font-bold text-lg leading-none">⚠️</span>
                <div>
                    <span class="font-bold">Regla institucional:</span> Solo se podrá promover al nivel inmediato superior de la jornada correspondiente.
                </div>
            </div>
        </div>

        <!-- Tabla Estilizada de Matriz de Categorías -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-12">
            
            <!-- Cabecera de la Tabla -->
            <div class="bg-slate-900 text-white px-6 py-4 grid grid-cols-1 sm:grid-cols-4 text-center sm:text-left font-bold text-xs tracking-wider uppercase">
                <div class="sm:col-span-1 flex items-center">Nivel / Rango</div>
                <div class="text-center">½ TIEMPO <span class="block text-[10px] text-slate-400 font-normal lowercase">(claves e36xx)</span></div>
                <div class="text-center">¾ TIEMPO <span class="block text-[10px] text-slate-400 font-normal lowercase">(claves e37xx)</span></div>
                <div class="text-center">TIEMPO COMPLETO <span class="block text-[10px] text-slate-400 font-normal lowercase">(claves e38xx)</span></div>
            </div>

            <!-- Filas de Niveles -->
            <div class="divide-y divide-slate-100 text-sm">
                
                <!-- Nivel 17 -->
                <div class="grid grid-cols-1 sm:grid-cols-4 p-5 items-center hover:bg-slate-50/50 transition">
                    <div class="font-bold text-slate-800">Nivel 17</div>
                    <div class="text-center text-slate-600">E3617 <span class="text-xs text-slate-400">+6a9 hrs</span></div>
                    <div class="text-center text-slate-600">E3717 <span class="text-xs text-slate-400">+6a9 hrs</span></div>
                    <div class="text-center">
                        <span class="inline-block px-5 py-1.5 bg-emerald-50 text-emerald-700 font-bold rounded-lg border border-emerald-200 shadow-2xs">
                            E3817
                        </span>
                    </div>
                </div>

                <!-- Nivel 15 -->
                <div class="grid grid-cols-1 sm:grid-cols-4 p-5 items-center hover:bg-slate-50/50 transition">
                    <div class="font-bold text-slate-800">Nivel 15</div>
                    <div class="text-center text-slate-600">E3615</div>
                    <div class="text-center text-slate-600">E3715</div>
                    <div class="text-center">
                        <span class="inline-block px-5 py-1.5 bg-emerald-50 text-emerald-700 font-bold rounded-lg border border-emerald-200 shadow-2xs">
                            E3815
                        </span>
                    </div>
                </div>

                <!-- Nivel 13 -->
                <div class="grid grid-cols-1 sm:grid-cols-4 p-5 items-center hover:bg-slate-50/50 transition">
                    <div class="font-bold text-slate-800">Nivel 13</div>
                    <div class="text-center text-slate-600">E3613</div>
                    <div class="text-center text-slate-600">E3713</div>
                    <div class="text-center">
                        <span class="inline-block px-5 py-1.5 bg-emerald-50 text-emerald-700 font-bold rounded-lg border border-emerald-200 shadow-2xs">
                            E3813
                        </span>
                    </div>
                </div>

                <!-- Nivel 11 -->
                <div class="grid grid-cols-1 sm:grid-cols-4 p-5 items-center hover:bg-slate-50/50 transition">
                    <div class="font-bold text-slate-800">Nivel 11</div>
                    <div class="text-center text-slate-600">E3611</div>
                    <div class="text-center text-slate-600">E3711</div>
                    <div class="text-center">
                        <span class="inline-block px-5 py-1.5 bg-emerald-50 text-emerald-700 font-bold rounded-lg border border-emerald-200 shadow-2xs">
                            E3811
                        </span>
                    </div>
                </div>

                <!-- Nivel 09 -->
                <div class="grid grid-cols-1 sm:grid-cols-4 p-5 items-center hover:bg-slate-50/50 transition">
                    <div class="font-bold text-slate-800">Nivel 09</div>
                    <div class="text-center text-slate-600">E3609</div>
                    <div class="text-center text-slate-600">E3709</div>
                    <div class="text-center">
                        <span class="inline-block px-5 py-1.5 bg-emerald-50 text-emerald-700 font-bold rounded-lg border border-emerald-200 shadow-2xs">
                            E3809
                        </span>
                    </div>
                </div>

                <!-- Nivel 07 -->
                <div class="grid grid-cols-1 sm:grid-cols-4 p-5 items-center hover:bg-slate-50/50 transition">
                    <div class="font-bold text-slate-800">Nivel 07</div>
                    <div class="text-center text-slate-600">E3607</div>
                    <div class="text-center text-slate-600">E3707</div>
                    <div class="text-center">
                        <span class="inline-block px-5 py-1.5 bg-emerald-50 text-emerald-700 font-bold rounded-lg border border-emerald-200 shadow-2xs">
                            E3807
                        </span>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection