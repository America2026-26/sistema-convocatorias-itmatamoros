@extends('layouts.app')

@section('content')
<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Tarjetas de Resumen Superior -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Postulaciones Activas -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Postulaciones Activas</p>
            <p class="text-3xl font-extrabold text-slate-900 mt-2">0</p>
        </div>

        <!-- Documentos Requeridos -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Documentos Requeridos</p>
            <p class="text-3xl font-extrabold text-indigo-600 mt-2">4 <span class="text-xl font-bold text-slate-700">Documentos</span></p>
        </div>

        <!-- Institución -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Institución</p>
            <p class="text-lg font-bold text-slate-800 mt-2">TecNM Campus Matamoros</p>
        </div>
    </div>

    <!-- Cabecera de la Sección -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="text-xs font-bold text-red-700 tracking-wider uppercase bg-red-50 px-2.5 py-1 rounded-md border border-red-100">Gestión de Expediente</span>
            <h2 class="text-2xl font-bold text-slate-900 mt-2">Documentación Requerida para Postulación</h2>
            <p class="text-sm text-slate-600">Sube y verifica que tus archivos en PDF estén actualizados antes de postularte a una plaza.</p>
        </div>
        <div class="bg-slate-100 border border-slate-200 text-slate-600 px-4 py-2 rounded-xl text-xs font-medium">
            Formatos permitidos: PDF (Máx. 5MB)
        </div>
    </div>

    <!-- Cuadrícula de Documentos Requeridos -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        
        <!-- Documento 1 -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between hover:shadow-md transition">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-red-50 text-red-600 rounded-xl text-xl">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 text-sm">Título y Cédula Profesional</h4>
                    <a href="#" class="text-xs text-indigo-600 hover:underline flex items-center mt-1">
                        <i class="fas fa-file-alt mr-1"></i> Titulo Profesional Carlos.pdf
                    </a>
                </div>
            </div>
            <div>
                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-full">
                    Documento Subido
                </span>
            </div>
        </div>

        <!-- Documento 2 -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between hover:shadow-md transition">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-red-50 text-red-600 rounded-xl text-xl">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 text-sm">Constancia de Servicios / Antigüedad</h4>
                    <a href="#" class="text-xs text-indigo-600 hover:underline flex items-center mt-1">
                        <i class="fas fa-file-alt mr-1"></i> Constancia Carlos.pdf
                    </a>
                </div>
            </div>
            <div>
                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-full">
                    Documento Subido
                </span>
            </div>
        </div>

        <!-- Documento 3 -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between hover:shadow-md transition">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-red-50 text-red-600 rounded-xl text-xl">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 text-sm">Currículum Vitae con Soporte</h4>
                    <a href="#" class="text-xs text-indigo-600 hover:underline flex items-center mt-1">
                        <i class="fas fa-file-alt mr-1"></i> Curriculum Carlos.pdf
                    </a>
                </div>
            </div>
            <div>
                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-full">
                    Documento Subido
                </span>
            </div>
        </div>

        <!-- Documento 4 -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between hover:shadow-md transition">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-red-50 text-red-600 rounded-xl text-xl">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 text-sm">Identificación Oficial (INE / Pasaporte)</h4>
                    <a href="#" class="text-xs text-indigo-600 hover:underline flex items-center mt-1">
                        <i class="fas fa-file-alt mr-1"></i> INE Carlos.pdf
                    </a>
                </div>
            </div>
            <div>
                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-full">
                    Documento Subido
                </span>
            </div>
        </div>

    </div>

    <!-- Historial de Postulaciones y Estatus -->
    <div class="mb-4">
        <h3 class="text-lg font-bold text-slate-900">Historial de Postulaciones y Estatus</h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-slate-600 font-semibold">
                <tr>
                    <th class="px-6 py-3 text-left">Convocatoria</th>
                    <th class="px-6 py-3 text-left">Fecha de Registro</th>
                    <th class="px-6 py-3 text-left">Estatus</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                <!-- Si no hay postulaciones registradas -->
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-slate-500 italic">
                        No cuentas con postulaciones registradas en este momento.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
@endsection