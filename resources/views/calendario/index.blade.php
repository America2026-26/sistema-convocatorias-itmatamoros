@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900">Cronograma Oficial de Etapas (Fechas Clave)</h1>
        <p class="mt-2 text-sm text-slate-600">Conoce los periodos oficiales establecidos en el Calendario Académico Agosto - Diciembre 2026 del Instituto Tecnológico de Matamoros.</p>
    </div>

    <!-- Grid de Fases Principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <!-- Fase 01 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-600 mb-4">FASE 01</span>
            <h3 class="font-bold text-lg text-slate-800 mb-2">Publicación</h3>
            <p class="text-xs text-slate-500 mb-6">Lanzamiento oficial de bases y requerimientos específicos de plazas.</p>
            <div class="flex items-center gap-2 text-xs font-bold text-blue-600">
                <i class="far fa-calendar-alt text-sm"></i>
                <span>03 Ago - 07 Ago 2026</span>
            </div>
        </div>

        <!-- Fase 02 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-600 mb-4">FASE 02</span>
            <h3 class="font-bold text-lg text-slate-800 mb-2">Registro y Carga</h3>
            <p class="text-xs text-slate-500 mb-6">Subida de expedientes digitales (Título, CV, INE y Constancias).</p>
            <div class="flex items-center gap-2 text-xs font-bold text-purple-600">
                <i class="far fa-calendar-alt text-sm"></i>
                <span>24 Ago - 04 Sep 2026</span>
            </div>
        </div>

        <!-- Fase 03 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 mb-4">FASE 03</span>
            <h3 class="font-bold text-lg text-slate-800 mb-2">Dictaminación</h3>
            <p class="text-xs text-slate-500 mb-6">Revisión de perfiles y validación técnica por el comité dictaminador.</p>
            <div class="flex items-center gap-2 text-xs font-bold text-amber-600">
                <i class="far fa-calendar-alt text-sm"></i>
                <span>21 Sep - 25 Sep 2026</span>
            </div>
        </div>

        <!-- Fase 04 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 mb-4">FASE 04</span>
            <h3 class="font-bold text-lg text-slate-800 mb-2">Resultados</h3>
            <p class="text-xs text-slate-500 mb-6">Publicación de dictámenes finales y notificación a seleccionados.</p>
            <div class="flex items-center gap-2 text-xs font-bold text-emerald-600">
                <i class="far fa-calendar-alt text-sm"></i>
                <span>11 Dic 2026</span>
            </div>
        </div>
    </div>

    <!-- Tabla Completa del Calendario Institucional -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6">
        <h3 class="font-bold text-xl text-slate-800 mb-6">Calendario Académico General - Período Agosto-Diciembre 2026</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[12px] uppercase text-slate-400 border-b border-slate-100">
                        <th class="py-4 px-4 font-bold tracking-widest">Actividad / Evento Institucional</th>
                        <th class="py-4 px-4 font-bold tracking-widest text-right">Período / Fecha Oficial</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <tr class="hover:bg-slate-50">
                        <td class="py-4 px-4 text-slate-700 font-medium">Inicio de actividades e Inscripciones nuevo ingreso</td>
                        <td class="py-4 px-4 text-slate-600 text-right font-mono text-xs">03 al 17 agosto 2026</td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="py-4 px-4 text-slate-700 font-medium">Reinscripciones del semestre agosto - diciembre 2026</td>
                        <td class="py-4 px-4 text-slate-600 text-right font-mono text-xs">10 y 11 agosto 2026</td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="py-4 px-4 text-slate-700 font-medium">Inicio de clases período agosto - diciembre 2026</td>
                        <td class="py-4 px-4 text-slate-600 text-right font-mono text-xs">24 agosto 2026</td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="py-4 px-4 text-slate-700 font-medium">Conmemoración Fundación Instituto Tecnológico de Matamoros (54 Aniversario)</td>
                        <td class="py-4 px-4 text-slate-600 text-right font-mono text-xs">02 septiembre 2026</td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="py-4 px-4 text-slate-700 font-medium">Primer reporte de avance de actividades docentes y calificaciones parciales</td>
                        <td class="py-4 px-4 text-slate-600 text-right font-mono text-xs">21 al 25 septiembre 2026</td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="py-4 px-4 text-slate-700 font-medium">91.ª Ceremonia de Titulación y Egreso (Generación 2022-2026)</td>
                        <td class="py-4 px-4 text-slate-600 text-right font-mono text-xs">23 octubre 2026</td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="py-4 px-4 text-slate-700 font-medium">Segundo reporte de avance y calificaciones parciales</td>
                        <td class="py-4 px-4 text-slate-600 text-right font-mono text-xs">26 al 30 octubre 2026</td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="py-4 px-4 text-slate-700 font-medium">Suspensión de labores (Día de Muertos)</td>
                        <td class="py-4 px-4 text-slate-600 text-right font-mono text-xs">02 noviembre 2026</td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="py-4 px-4 text-slate-700 font-medium">Suspensión de labores (Aniversario de la Revolución Mexicana)</td>
                        <td class="py-4 px-4 text-slate-600 text-right font-mono text-xs">16 noviembre 2026</td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="py-4 px-4 text-slate-700 font-medium">Tercer reporte de avance de actividades docentes y calificaciones parciales</td>
                        <td class="py-4 px-4 text-slate-600 text-right font-mono text-xs">30 nov al 04 dic 2026</td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="py-4 px-4 text-slate-700 font-medium">Fin de clases y término de cursos de lenguas extranjeras</td>
                        <td class="py-4 px-4 text-slate-600 text-right font-mono text-xs">11 diciembre 2026</td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="py-4 px-4 text-slate-700 font-medium">Fecha límite de captura de calificaciones en el SII2</td>
                        <td class="py-4 px-4 text-slate-600 text-right font-mono text-xs">14 diciembre 2026</td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="py-4 px-4 text-slate-700 font-medium">Periodo vacacional de diciembre</td>
                        <td class="py-4 px-4 text-slate-600 text-right font-mono text-xs">21 dic 2026 al 05 ene 2027</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Nota inferior de contacto -->
    <div class="mt-8 text-center text-xs text-slate-500">
        <p>Para mayor información o dudas sobre las fechas específicas de tu proceso, comunícate directamente al departamento de Gestión Tecnológica y Vinculación del Instituto Tecnológico de Matamoros.</p>
    </div>
</div>
@endsection
[cite: 19, 20, 21]