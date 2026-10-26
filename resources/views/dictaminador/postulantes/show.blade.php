@extends('layouts.app')

@section('title', 'Expediente de postulante - Panel del Dictaminador')

@section('content')
    <section class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <a href="{{ route('dictaminador.postulantes.index', ['convocatoria' => $postulacion->convocatoria_id]) }}" class="text-sm font-semibold text-rose-900 hover:underline">&larr; Volver a postulantes</a>

        @if (session('status'))
            <p role="status" class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('status') }}</p>
        @endif
        @if ($errors->any())
            <div role="alert" class="mt-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800">@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
        @endif

        <article class="mt-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-start">
                <div>
                    <span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-900">{{ $postulacion->convocatoria->tipo_personal }}</span>
                    <h1 class="mt-3 text-2xl font-bold text-gray-900">{{ $postulacion->user->name }}</h1>
                    <p class="mt-1 text-sm text-gray-600">{{ $postulacion->convocatoria->titulo }}</p>
                </div>
                <span class="rounded-full bg-gray-100 px-3 py-1.5 text-sm font-semibold text-gray-700">{{ ucfirst($postulacion->estatus) }}</span>
            </div>

            <dl class="mt-6 grid grid-cols-1 gap-4 border-t border-gray-100 pt-5 sm:grid-cols-2">
                @foreach ([
                    'Correo' => data_get($postulacion->informacion, 'perfil.email', $postulacion->user->email),
                    'Tipo de perfil' => data_get($postulacion->informacion, 'perfil.tipo_usuario', '—'),
                    'CURP' => data_get($postulacion->informacion, 'perfil.curp', '—'),
                    'RFC' => data_get($postulacion->informacion, 'perfil.rfc', '—'),
                    'Fecha de postulación' => $postulacion->created_at->format('d/m/Y H:i'),
                ] as $label => $value)
                    <div><dt class="text-xs font-semibold uppercase text-gray-500">{{ $label }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $value }}</dd></div>
                @endforeach
            </dl>

            @if (data_get($postulacion->informacion, 'texto'))
                <div class="mt-6 border-t border-gray-100 pt-5">
                    <h2 class="text-sm font-bold text-gray-900">Información proporcionada</h2>
                    <p class="mt-2 whitespace-pre-line text-sm text-gray-700">{{ data_get($postulacion->informacion, 'texto') }}</p>
                </div>
            @endif

            <div class="mt-6 border-t border-gray-100 pt-5">
                <h2 class="text-sm font-bold text-gray-900">Documento PDF</h2>
                @if ($postulacion->documento_path)
                    <a href="{{ route('dictaminador.postulantes.document', $postulacion) }}" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex rounded-lg border border-rose-200 px-3 py-2 text-sm font-semibold text-rose-900 hover:bg-rose-50">Abrir expediente PDF</a>
                @else
                    <p class="mt-2 text-sm text-gray-500">No se adjuntó un documento PDF.</p>
                @endif
            </div>

            @if ($postulacion->comentarios_revision)
                <div class="mt-6 rounded-lg bg-gray-50 p-4">
                    <h2 class="text-sm font-bold text-gray-900">Observaciones del dictamen</h2>
                    <p class="mt-2 whitespace-pre-line text-sm text-gray-700">{{ $postulacion->comentarios_revision }}</p>
                </div>
            @endif

            @if ($postulacion->estatus === \App\Models\Postulacion::STATUS_PENDING)
                <form action="{{ route('dictaminador.postulantes.review', $postulacion) }}" method="POST" class="mt-6 border-t border-gray-100 pt-5">
                    @csrf
                    @method('PATCH')
                    <h2 class="font-bold text-gray-900">Dictaminar expediente</h2>
                    <label for="comentarios_revision" class="mb-1 mt-4 block text-sm font-semibold text-gray-700">Observaciones (requeridas para rechazar)</label>
                    <textarea id="comentarios_revision" name="comentarios_revision" rows="4" maxlength="5000" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-900">{{ old('comentarios_revision') }}</textarea>
                    <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                        <button name="estatus" value="aprobada" class="rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-600">Aprobar información</button>
                        <button name="estatus" value="rechazada" class="rounded-lg bg-red-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-600">Rechazar información</button>
                    </div>
                </form>
            @else
                <p class="mt-6 border-t border-gray-100 pt-5 text-sm text-gray-600">Dictaminado por {{ $postulacion->revisor?->name ?? 'Dictaminador' }} el {{ $postulacion->revisado_en?->format('d/m/Y H:i') }}.</p>
            @endif
        </article>
    </section>
@endsection
