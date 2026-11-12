@extends('layouts.app')

@section('title', 'Estatus de postulación - Sistema de Convocatorias')

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <a href="{{ route('postulaciones.index') }}" class="text-sm font-semibold text-rose-900 hover:underline">&larr; Mis postulaciones</a>
        @php($perfil = data_get($postulacion->informacion, 'perfil', []))
        @if (session('status'))
            <p role="status" class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('status') }}</p>
        @endif
        <article class="mt-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-900">{{ $postulacion->convocatoria->tipo_personal }}</span>
            <h1 class="mt-3 text-2xl font-bold text-gray-900">{{ $postulacion->convocatoria->titulo }}</h1>
            <p class="mt-3 text-sm text-gray-600">Enviada el {{ $postulacion->created_at->format('d/m/Y H:i') }}</p>
            <p class="mt-4 inline-flex rounded-full bg-gray-100 px-3 py-1.5 text-sm font-semibold text-gray-800">Estatus: {{ ucfirst($postulacion->estatus) }}</p>

            <section class="mt-6 border-t border-gray-100 pt-5">
                <h2 class="font-bold text-gray-900">Información compartida</h2>
                <dl class="mt-3 grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
                    <div><dt class="text-xs font-semibold text-gray-500">Nombre</dt><dd class="mt-1 text-gray-900">{{ trim(collect([$perfil['nombre'] ?? null, $perfil['apellido_paterno'] ?? null, $perfil['apellido_materno'] ?? null])->filter()->implode(' ')) ?: $postulacion->user->name }}</dd></div>
                    <div><dt class="text-xs font-semibold text-gray-500">Correo</dt><dd class="mt-1 text-gray-900">{{ $perfil['email'] ?? $postulacion->user->email }}</dd></div>
                    <div><dt class="text-xs font-semibold text-gray-500">CURP</dt><dd class="mt-1 text-gray-900">{{ $perfil['curp'] ?? 'No proporcionada' }}</dd></div>
                    <div><dt class="text-xs font-semibold text-gray-500">RFC</dt><dd class="mt-1 text-gray-900">{{ $perfil['rfc'] ?? 'No proporcionado' }}</dd></div>
                    <div><dt class="text-xs font-semibold text-gray-500">Tipo de perfil</dt><dd class="mt-1 text-gray-900">{{ $perfil['tipo_usuario'] ?? 'No especificado' }}</dd></div>
                </dl>
                @if (data_get($postulacion->informacion, 'texto'))
                    <div class="mt-4">
                        <h3 class="text-sm font-semibold text-gray-700">Información adicional</h3>
                        <p class="mt-1 whitespace-pre-line text-sm text-gray-700">{{ data_get($postulacion->informacion, 'texto') }}</p>
                    </div>
                @endif
            </section>

            <section class="mt-6 border-t border-gray-100 pt-5">
                <h2 class="font-bold text-gray-900">Documentación enviada</h2>
                @if ($postulacion->documentos->isNotEmpty())
                    <ul class="mt-3 space-y-3">
                        @foreach ($postulacion->documentos as $documento)
                            <li class="flex flex-col justify-between gap-2 rounded-lg border border-gray-200 p-3 sm:flex-row sm:items-center">
                                <span class="text-sm font-medium text-gray-800">{{ $documento->nombre }}</span>
                                <a href="{{ route('postulaciones.documento-requerido', [$postulacion, $documento]) }}" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold text-rose-900 hover:underline">Ver PDF</a>
                            </li>
                        @endforeach
                    </ul>
                @elseif ($postulacion->documento_path)
                    <p class="mt-3">
                        <a href="{{ route('postulaciones.documento', $postulacion) }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-rose-900 hover:underline">Ver documento enviado (PDF)</a>
                    </p>
                @else
                    <p class="mt-2 text-sm text-gray-500">No se registraron documentos para esta postulación.</p>
                @endif
            </section>

            @if ($postulacion->comentarios_revision)
                <div class="mt-6 rounded-lg bg-rose-50 p-4">
                    <h2 class="font-semibold text-rose-950">Observaciones del dictaminador</h2>
                    <p class="mt-2 whitespace-pre-line text-sm text-gray-700">{{ $postulacion->comentarios_revision }}</p>
                </div>
            @endif
        </article>
    </section>
@endsection
