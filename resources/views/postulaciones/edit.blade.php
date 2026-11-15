@extends('layouts.app')

@section('title', 'Editar expediente - Sistema de Convocatorias')

@section('content')
    <section class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <x-back-button href="{{ route('postulaciones.show', $postulacion) }}" label="Volver al expediente" />
        <h1 class="mt-4 text-2xl font-bold text-gray-900">Corregir expediente</h1>
        <p class="mt-2 text-sm text-gray-600">{{ $postulacion->convocatoria->titulo }}</p>
        <p class="mt-2 text-sm text-gray-600">Puedes reemplazar cualquier PDF o actualizar la información adicional. Los archivos actuales se conservan si no los reemplazas.</p>

        <form action="{{ route('postulaciones.update', $postulacion) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PATCH')

            @if ($errors->any())
                <div role="alert" class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <section>
                <h2 class="text-lg font-bold text-gray-900">Documentos del expediente</h2>
                <div class="mt-4 space-y-4">
                    @foreach ($documentos as $key => $documento)
                        @php($documentoActual = $postulacion->documentos->firstWhere('clave', $key))
                        <div class="rounded-lg border border-gray-200 p-4">
                            <label for="documento_{{ $key }}" class="block text-sm font-semibold text-gray-900">{{ $documento['label'] }}</label>
                            <p class="mt-1 text-sm text-gray-600">{{ $documento['description'] }}</p>
                            @if ($documentoActual)
                                <a href="{{ route('postulaciones.documento-requerido', [$postulacion, $documentoActual]) }}" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex text-sm font-semibold text-rose-900 hover:underline">Ver PDF actual</a>
                            @endif
                            <input id="documento_{{ $key }}" type="file" name="documentos[{{ $key }}]" accept="application/pdf,.pdf"
                                class="mt-3 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                            <p class="mt-1 text-xs text-gray-500">Opcional · PDF de hasta 10 MB. Déjalo vacío para conservar el archivo actual.</p>
                            @foreach ($errors->get('documentos.'.$key) as $error)
                                <p class="mt-1 text-sm text-red-700">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </section>

            <div>
                <label for="informacion" class="mb-1 block text-sm font-semibold text-gray-700">Información adicional</label>
                <textarea id="informacion" name="informacion" rows="4" maxlength="10000"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-900">{{ old('informacion', data_get($postulacion->informacion, 'texto')) }}</textarea>
            </div>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('postulaciones.show', $postulacion) }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="rounded-lg bg-rose-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-rose-800">Guardar cambios y reenviar</button>
            </div>
        </form>
    </section>
@endsection
