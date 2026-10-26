@extends('layouts.app')

@section('title', 'Mis postulaciones - Sistema de Convocatorias')

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900">Mis postulaciones</h1>
        <p class="mt-1 text-sm text-gray-600">Consulta el seguimiento y los dictámenes de tus solicitudes.</p>

        <div class="mt-6 space-y-4">
            @forelse ($postulaciones as $postulacion)
                <article class="flex flex-col justify-between gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center">
                    <div>
                        <span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-900">{{ $postulacion->convocatoria->tipo_personal }}</span>
                        <h2 class="mt-2 font-bold text-gray-900">{{ $postulacion->convocatoria->titulo }}</h2>
                        <p class="mt-1 text-sm text-gray-600">Enviada el {{ $postulacion->created_at->format('d/m/Y H:i') }} · Estatus: {{ ucfirst($postulacion->estatus) }}</p>
                    </div>
                    <a href="{{ route('postulaciones.show', $postulacion) }}" class="rounded-lg border border-gray-300 px-4 py-2 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50">Ver dictamen</a>
                </article>
            @empty
                <p class="rounded-xl border border-gray-200 bg-white p-8 text-center text-sm text-gray-500">Aún no tienes postulaciones.</p>
            @endforelse
        </div>
        <div class="mt-4">{{ $postulaciones->links() }}</div>
    </section>
@endsection
