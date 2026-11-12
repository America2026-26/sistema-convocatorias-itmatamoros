@extends('layouts.app')

@section('content')
<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Tarjetas Superiores de Resumen -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Postulaciones Activas</span>
            <div class="text-3xl font-bold text-slate-900 mt-2">0</div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Documentos Requeridos</span>
            <div class="text-3xl font-bold text-blue-600 mt-2">4 Documentos</div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Institución</span>
            <div class="text-lg font-bold text-slate-800 mt-2">TecNM Campus Matamoros</div>
        </div>
    </div>

    <!-- Sección Título con Etiqueta Rosa -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-3 py-1 bg-pink-100 text-pink-700 text-xs font-bold rounded-md uppercase tracking-wide mb-2">GESTIÓN DE EXPEDIENTE</span>
            <h2 class="text-xl font-bold text-slate-900">Documentación Requerida para Postulación</h2>
            <p class="text-sm text-slate-600">Sube y verifica que tus archivos en PDF estén actualizados antes de postularte a una plaza.</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-lg px-4 py-2 text-xs text-slate-500 shadow-sm">
            Formatos permitidos: <span class="font-semibold text-slate-700">PDF (Máx. 5MB)</span>
        </div>
    </div>

    <!-- Grid de Documentos -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        
        <!-- Documento 1 -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-red-50 text-red-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-sm">Título y Cédula Profesional</h4>
                    <span class="text-xs text-blue-600 hover:underline cursor-pointer flex items-center mt-0.5">📄 Titulo Profesional Carlos.pdf</span>
                </div>
            </div>
            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-lg">Documento Subido</span>
        </div>

        <!-- Documento 2 -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-red-50 text-red-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-sm">Constancia de Servicios / Antigüedad</h4>
                    <span class="text-xs text-blue-600 hover:underline cursor-pointer flex items-center mt-0.5">📄 Constancia Carlos.pdf</span>
                </div>
            </div>
            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-lg">Documento Subido</span>
        </div>

        <!-- Documento 3 -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-red-50 text-red-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-sm">Currículum Vitae con Soporte</h4>
                    <span class="text-xs text-blue-600 hover:underline cursor-pointer flex items-center mt-0.5">📄 Curriculum Carlos.pdf</span>
                </div>
            </div>
            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-lg">Documento Subido</span>
        </div>

        <!-- Documento 4 -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-red-50 text-red-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-sm">Identificación Oficial (INE / Pasaporte)</h4>
                    <span class="text-xs text-blue-600 hover:underline cursor-pointer flex items-center mt-0.5">📄 INE Carlos.pdf</span>
                </div>
            </div>
            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-lg">Documento Subido</span>
        </div>

    </div>

    <!-- Historial de Postulaciones -->
    <div class="mb-4">
        <h3 class="text-lg font-bold text-slate-900">Historial de Postulaciones y Estatus</h3>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50/70 text-xs uppercase text-slate-500 font-semibold">
                    <th class="py-3 px-6">Convocatoria</th>
                    <th class="py-3 px-6">Fecha de Registro</th>
                    <th class="py-3 px-6">Estatus</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                <tr>
                    <td colspan="3" class="py-6 text-center text-slate-400">No hay postulaciones registradas en el historial.</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
@endsection