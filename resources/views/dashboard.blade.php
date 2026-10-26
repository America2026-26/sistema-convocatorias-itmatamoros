@extends('layouts.app')

@section('title', 'Panel del Aspirante - Sistema de Convocatorias ITM')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col justify-between gap-4 rounded-xl border border-rose-200 bg-rose-50 p-6 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-rose-950">Hola, {{ auth()->user()->name }}</h1>
                <p class="mt-1 text-sm text-rose-800">Consulta las convocatorias, presenta tu postulación y revisa las notificaciones.</p>
            </div>
            <a href="{{ route('home') }}#convocatorias"
                class="inline-flex items-center justify-center rounded-lg bg-rose-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-rose-800">
                Ver convocatorias
            </a>
        </div>

        <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2">
            <a href="{{ route('postulaciones.index') }}" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-rose-700">
                <p class="text-sm text-gray-600">Mis postulaciones</p>
                <p class="mt-2 text-3xl font-bold text-rose-950">{{ $postulaciones->total() }}</p>
            </a>
            <a href="{{ route('notifications.index') }}" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-rose-700">
                <p class="text-sm text-gray-600">Notificaciones nuevas</p>
                <p class="mt-2 text-3xl font-bold text-rose-950">{{ $unreadNotificationsCount }}</p>
            </a>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 bg-gray-50 px-5 py-4">
                <h2 class="font-bold text-gray-900">Seguimiento de postulaciones</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[650px] text-left text-sm">
                    <thead class="bg-white text-xs uppercase text-gray-500">
                        <tr>
                            <th scope="col" class="px-5 py-3">Convocatoria</th>
                            <th scope="col" class="px-5 py-3">Tipo</th>
                            <th scope="col" class="px-5 py-3">Fecha</th>
                            <th scope="col" class="px-5 py-3">Estatus</th>
                            <th scope="col" class="px-5 py-3">Detalle</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($postulaciones as $postulacion)
                            <tr>
                                <td class="px-5 py-4 font-medium text-gray-900">{{ $postulacion->convocatoria->titulo }}</td>
                                <td class="px-5 py-4 text-gray-700">{{ $postulacion->convocatoria->tipo_personal }}</td>
                                <td class="px-5 py-4 text-gray-700">{{ $postulacion->created_at->format('d/m/Y') }}</td>
                                <td class="px-5 py-4 text-gray-700">{{ ucfirst($postulacion->estatus) }}</td>
                                <td class="px-5 py-4">
                                    <a href="{{ route('postulaciones.show', $postulacion) }}" class="font-semibold text-rose-900 hover:underline">Ver</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-8 text-center text-gray-500">Aún no tienes postulaciones.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">{{ $postulaciones->links() }}</div>
    </section>
@endsection
