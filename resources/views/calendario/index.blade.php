@extends('layouts.app')

@section('content')
<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Título Principal -->
    <div class="mb-6 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h1 class="text-2xl font-bold text-slate-900">Cronograma Oficial de Etapas (Fechas Clave)</h1>
        <p class="text-sm text-slate-600 mt-1">Conoce los periodos oficiales establecidos para el desarrollo de la convocatoria.</p>
    </div>

    <!-- Grid de Fases / Tarjetas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- FASE 01 -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div>
                <span class="text-xs font-bold tracking-wider text-blue-600 uppercase bg-blue-50 px-2.5 py-1 rounded-md">FASE 01</span>
                <h3 class="text-lg font-bold text-slate-900 mt-3">Publicación</h3>
                <p class="text-sm text-slate-600 mt-2">Lanzamiento oficial de bases y requerimientos específicos de plazas.</p>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center text-sm font-medium text-slate-700">
                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                01 Nov - 05 Nov
            </div>
        </div>

        <!-- FASE 02 -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div>
                <span class="text-xs font-bold tracking-wider text-indigo-600 uppercase bg-indigo-50 px-2.5 py-1 rounded-md">FASE 02</span>
                <h3 class="text-lg font-bold text-slate-900 mt-3">Registro y Carga</h3>
                <p class="text-sm text-slate-600 mt-2">Subida de expedientes digitales (Título, CV, INE y Constancias).</p>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center text-sm font-medium text-slate-700">
                <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                06 Nov - 15 Nov
            </div>
        </div>

        <!-- FASE 03 -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div>
                <span class="text-xs font-bold tracking-wider text-amber-600 uppercase bg-amber-50 px-2.5 py-1 rounded-md">FASE 03</span>
                <h3 class="text-lg font-bold text-slate-900 mt-3">Dictaminación</h3>
                <p class="text-sm text-slate-600 mt-2">Revisión de perfiles y validación técnica por el comité dictaminador.</p>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center text-sm font-medium text-slate-700">
                <svg class="w-4 h-4 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                16 Nov - 22 Nov
            </div>
        </div>

        <!-- FASE 04 -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div>
                <span class="text-xs font-bold tracking-wider text-emerald-600 uppercase bg-emerald-50 px-2.5 py-1 rounded-md">FASE 04</span>
                <h3 class="text-lg font-bold text-slate-900 mt-3">Resultados</h3>
                <p class="text-sm text-slate-600 mt-2">Publicación de dictámenes finales y notificación a seleccionados.</p>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center text-sm font-medium text-slate-700">
                <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                25 de Noviembre
            </div>
        </div>

    </div>

    <!-- Nota inferior informativa -->
    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-center text-sm text-slate-600">
        Para mayor información o dudas sobre las fechas específicas de tu proceso, comunícate directamente al departamento de <strong>Gestión Tecnológica y Vinculación</strong> del Instituto Tecnológico de Matamoros.
    </div>

</div>
@endsection