@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
            
            <!-- Encabezado -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b pb-4 mb-6 gap-3">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Estado de Expediente Digital</h3>
                    <p class="text-sm text-gray-500">Consulta el estatus de validación de tus documentos y el historial de tus postulaciones.</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                    Expediente Registrado
                </span>
            </div>

            <!-- Tarjetas de Resumen -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <p class="text-xs text-slate-500 font-medium">Postulaciones Activas</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1">{{ $postulaciones->count() }}</p>
                </div>
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <p class="text-xs text-slate-500 font-medium">Documentos Requeridos</p>
                    <p class="text-2xl font-bold text-indigo-600 mt-1">4 Documentos</p>
                </div>
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <p class="text-xs text-slate-500 font-medium">Institución</p>
                    <p class="text-sm font-semibold text-slate-800 mt-1">TecNM Campus Matamoros</p>
                </div>
            </div>

            <!-- Sección: Documentación Requerida para Postulación (Inspirada en tu diseño) -->
            <div class="mb-10">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                    <div>
                        <span class="text-[11px] font-bold text-rose-800 uppercase tracking-wider bg-rose-50 px-2.5 py-1 rounded-md">Gestión de Expediente</span>
                        <h4 class="font-bold text-slate-800 text-base mt-1">Documentación Requerida para Postulación</h4>
                        <p class="text-xs text-gray-500">Sube y verifica que tus archivos en PDF estén actualizados antes de postularte a una plaza.</p>
                    </div>
                    <span class="text-xs text-slate-500 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200 self-start sm:self-auto">
                        Formatos permitidos: <span class="font-semibold text-slate-700">PDF (Máx. 5MB)</span>
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <!-- Documento 1 -->
                    <div class="flex items-center justify-between p-4 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="p-2.5 bg-rose-50 rounded-lg text-rose-700 mt-0.5">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z" />
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-semibold text-slate-800 text-sm">Título y Cédula Profesional</h5>
                                <p class="text-xs text-amber-600 font-medium mt-0.5">Pendiente de adjuntar</p>
                            </div>
                        </div>
                        <a href="{{ route('profile.show') }}" class="px-3.5 py-1.5 text-xs font-semibold text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                            Adjuntar PDF
                        </a>
                    </div>

                    <!-- Documento 2 -->
                    <div class="flex items-center justify-between p-4 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="p-2.5 bg-rose-50 rounded-lg text-rose-700 mt-0.5">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z" />
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-semibold text-slate-800 text-sm">Constancia de Servicios / Antigüedad</h5>
                                <p class="text-xs text-amber-600 font-medium mt-0.5">Pendiente de adjuntar</p>
                            </div>
                        </div>
                        <a href="{{ route('profile.show') }}" class="px-3.5 py-1.5 text-xs font-semibold text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                            Adjuntar PDF
                        </a>
                    </div>

                    <!-- Documento 3 -->
                    <div class="flex items-center justify-between p-4 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="p-2.5 bg-rose-50 rounded-lg text-rose-700 mt-0.5">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z" />
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-semibold text-slate-800 text-sm">Currículum Vitae con Soporte</h5>
                                <p class="text-xs text-amber-600 font-medium mt-0.5">Pendiente de adjuntar</p>
                            </div>
                        </div>
                        <a href="{{ route('profile.show') }}" class="px-3.5 py-1.5 text-xs font-semibold text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                            Adjuntar PDF
                        </a>
                    </div>

                    <!-- Documento 4 -->
                    <div class="flex items-center justify-between p-4 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="p-2.5 bg-rose-50 rounded-lg text-rose-700 mt-0.5">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z" />
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-semibold text-slate-800 text-sm">Identificación Oficial (INE / Pasaporte)</h5>
                                <p class="text-xs text-amber-600 font-medium mt-0.5">Pendiente de adjuntar</p>
                            </div>
                        </div>
                        <a href="{{ route('profile.show') }}" class="px-3.5 py-1.5 text-xs font-semibold text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                            Adjuntar PDF
                        </a>
                    </div>

                </div>
            </div>

            <!-- Sección: Historial de Postulaciones -->
            <div>
                <h4 class="font-semibold text-md text-slate-800 mb-3">Historial de Postulaciones y Estatus</h4>
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">Convocatoria</th>
                                <th class="px-4 py-3 text-left font-semibold">Fecha de Registro</th>
                                <th class="px-4 py-3 text-left font-semibold">Estatus</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($postulaciones as $postulacion)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $postulacion->convocatoria->titulo ?? 'Convocatoria Docente/No Docente' }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $postulacion->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">
                                            {{ ucfirst($postulacion->status ?? 'En revisión') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                        No cuentas con postulaciones registradas actualmente.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection