@extends('layouts.app')

@section('title', 'Estatus de postulación - Sistema de Convocatorias')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <x-back-button href="{{ route('postulaciones.index') }}" label="Volver a mis postulaciones" />
        @php($perfil = data_get($postulacion->informacion, 'perfil', []))
        @if (session('status'))
            <p role="status" class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('status') }}</p>
        @endif
        @if ($postulacion->estatus === \App\Models\Postulacion::STATUS_RETURNED)
            <div class="mt-4 flex flex-col gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-amber-950">El administrador devolvió tu expediente para correcciones. Para modificar documentos o información adicional, usa el botón; el expediente seguirá en solo lectura fuera del formulario.</p>
                <a href="{{ route('postulaciones.edit', $postulacion) }}" class="shrink-0 rounded-lg bg-amber-700 px-4 py-2 text-center text-sm font-semibold text-white transition hover:bg-amber-800">Editar expediente</a>
            </div>
        @endif
        <article class="mt-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-900">{{ $postulacion->convocatoria->tipo_personal }}</span>
            <h1 class="mt-3 text-2xl font-bold text-gray-900">{{ $postulacion->convocatoria->titulo }}</h1>
            <p class="mt-3 text-sm text-gray-600">Enviada el {{ $postulacion->created_at->format('d/m/Y H:i') }}</p>
            <p class="mt-4 inline-flex rounded-full bg-gray-100 px-3 py-1.5 text-sm font-semibold text-gray-800">
                Estatus: {{ $postulacion->estatus === \App\Models\Postulacion::STATUS_RETURNED ? 'Devuelta para edición' : ucfirst($postulacion->estatus) }}
            </p>

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

            <x-postulacion-pdf-viewer
                :postulacion="$postulacion"
                :documents="$postulacion->documentos"
                document-route="postulaciones.documento-requerido"
                :legacy-url="$postulacion->documento_path ? route('postulaciones.documento', $postulacion) : null"
                heading="Documentación enviada"
                empty-message="No se registraron documentos para esta postulación."
            />

            @if ($postulacion->comentarios_revision)
                <div class="mt-6 rounded-lg bg-rose-50 p-4">
                    <h2 class="font-semibold text-rose-950">Observaciones</h2>
                    <p class="mt-2 whitespace-pre-line text-sm text-gray-700">{{ $postulacion->comentarios_revision }}</p>
                </div>
            @endif
        </article>
    </section>
@endsection
