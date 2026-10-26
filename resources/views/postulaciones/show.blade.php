@extends('layouts.app')

@section('title', 'Estatus de postulación - Sistema de Convocatorias')

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <a href="{{ route('postulaciones.index') }}" class="text-sm font-semibold text-rose-900 hover:underline">&larr; Mis postulaciones</a>
        @if (session('status'))
            <p role="status" class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('status') }}</p>
        @endif
        <article class="mt-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-900">{{ $postulacion->convocatoria->tipo_personal }}</span>
            <h1 class="mt-3 text-2xl font-bold text-gray-900">{{ $postulacion->convocatoria->titulo }}</h1>
            <p class="mt-3 text-sm text-gray-600">Enviada el {{ $postulacion->created_at->format('d/m/Y H:i') }}</p>
            <p class="mt-4 inline-flex rounded-full bg-gray-100 px-3 py-1.5 text-sm font-semibold text-gray-800">Estatus: {{ ucfirst($postulacion->estatus) }}</p>

            @if ($postulacion->comentarios_revision)
                <div class="mt-6 rounded-lg bg-rose-50 p-4">
                    <h2 class="font-semibold text-rose-950">Observaciones del dictaminador</h2>
                    <p class="mt-2 whitespace-pre-line text-sm text-gray-700">{{ $postulacion->comentarios_revision }}</p>
                </div>
            @endif

            @if ($postulacion->documento_path)
                <p class="mt-5">
                    <a href="{{ route('postulaciones.documento', $postulacion) }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-rose-900 hover:underline">Ver documento enviado (PDF)</a>
                </p>
            @endif
        </article>
    </section>
@endsection
