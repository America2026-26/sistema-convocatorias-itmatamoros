@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
            
            <!-- Encabezado -->
            <div class="border-b pb-4 mb-6">
                <h3 class="text-lg font-bold text-slate-800">Calendario Institucional y Fechas Clave</h3>
                <p class="text-sm text-gray-500">Cronograma oficial de aperturas, periodos de registro y fases de dictaminación del Instituto Tecnológico de Matamoros.</p>
            </div>

            <!-- Sección de Convocatorias Activas / De Ejemplo -->
            <div class="mb-8">
                <h4 class="font-semibold text-md text-slate-800 mb-4">Proceso de Selección Vigente</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <!-- Tarjeta Convocatoria Docente -->
                    <div class="p-5 rounded-xl border border-indigo-100 bg-gradient-to-br from-indigo-50/50 to-white shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[11px] font-bold text-indigo-700 bg-indigo-100 px-2.5 py-1 rounded-md uppercase tracking-wider">Convocatoria Docente</span>
                                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full">Abierta</span>
                            </div>
                            <h5 class="font-bold text-slate-800 text-base">Proceso de Ocupación de Plazas Docentes 2026</h5>
                            <p class="text-xs text-slate-600 mt-1">Convocatoria dirigida a profesionales interesados en impartir asignaturas en sistemas computacionales y ciencias básicas.</p>
                        </div>
                        <div class="mt-4 pt-4 border-t border-indigo-100 flex items-center justify-between text-xs">
                            <span class="text-slate-500">Cierre de recepción:</span>
                            <span class="font-bold text-slate-800 bg-white px-2.5 py-1 rounded border border-slate-200">15 de Noviembre, 2026</span>
                        </div>
                    </div>

                    <!-- Tarjeta Convocatoria No Docente -->
                    <div class="p-5 rounded-xl border border-slate-200 bg-slate-50 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[11px] font-bold text-slate-700 bg-slate-200 px-2.5 py-1 rounded-md uppercase tracking-wider">Personal de Apoyo</span>
                                <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2.5 py-0.5 rounded-full">Próximamente</span>
                            </div>
                            <h5 class="font-bold text-slate-800 text-base">Convocatoria Personal Administrativo y de Apoyo</h5>
                            <p class="text-xs text-slate-600 mt-1">Reclutamiento para áreas administrativas y de gestión tecnológica institucional.</p>
                        </div>
                        <div class="mt-4 pt-4 border-t border-slate-200 flex items-center justify-between text-xs">
                            <span class="text-slate-500">Apertura estimada:</span>
                            <span class="font-bold text-slate-800 bg-white px-2.5 py-1 rounded border border-slate-200">01 de Diciembre, 2026</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Línea de tiempo estructurada con fechas de ejemplo -->
            <div class="mt-8">
                <h4 class="font-semibold text-md text-slate-800 mb-6">Cronograma Oficial de Etapas (Fechas Clave)</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    
                    <!-- Paso 1 -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 relative flex flex-col justify-between">
                        <div>
                            <span class="text-xs font-bold text-indigo-600 block mb-1">FASE 01</span>
                            <h5 class="font-bold text-slate-800 text-sm">Publicación</h5>
                            <p class="text-xs text-slate-500 mt-1">Lanzamiento oficial de bases y requerimientos específicos de plazas.</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-200">
                            <span class="text-[11px] font-semibold text-slate-700 block">📅 01 Nov - 05 Nov</span>
                        </div>
                    </div>

                    <!-- Paso 2 -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 relative flex flex-col justify-between">
                        <div>
                            <span class="text-xs font-bold text-indigo-600 block mb-1">FASE 02</span>
                            <h5 class="font-bold text-slate-800 text-sm">Registro y Carga</h5>
                            <p class="text-xs text-slate-500 mt-1">Subida de expedientes digitales (Título, CV, INE y Constancias).</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-200">
                            <span class="text-[11px] font-semibold text-slate-700 block">📅 06 Nov - 15 Nov</span>
                        </div>
                    </div>

                    <!-- Paso 3 -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 relative flex flex-col justify-between">
                        <div>
                            <span class="text-xs font-bold text-indigo-600 block mb-1">FASE 03</span>
                            <h5 class="font-bold text-slate-800 text-sm">Dictaminación</h5>
                            <p class="text-xs text-slate-500 mt-1">Revisión de perfiles y validación técnica por el comité dictaminador.</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-200">
                            <span class="text-[11px] font-semibold text-slate-700 block">📅 16 Nov - 22 Nov</span>
                        </div>
                    </div>

                    <!-- Paso 4 -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 relative flex flex-col justify-between">
                        <div>
                            <span class="text-xs font-bold text-indigo-600 block mb-1">FASE 04</span>
                            <h5 class="font-bold text-slate-800 text-sm">Resultados</h5>
                            <p class="text-xs text-slate-500 mt-1">Publicación de dictámenes finales y notificación a seleccionados.</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-200">
                            <span class="text-[11px] font-semibold text-slate-700 block">📅 25 de Noviembre</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Pie de ayuda -->
            <div class="mt-8 p-4 bg-slate-50 rounded-xl border border-slate-200 text-center">
                <p class="text-xs text-slate-600">
                    Para mayor información o dudas sobre las fechas específicas de tu proceso, comunícate directamente al departamento de 
                    <span class="font-semibold text-slate-800">Gestión Tecnológica y Vinculación</span> del Instituto Tecnológico de Matamoros.
                </p>
            </div>

        </div>
    </div>
</div>
@endsection