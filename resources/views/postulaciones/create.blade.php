@extends('layouts.app')

@section('title', 'Postularme - '.$convocatoria->titulo)

@section('content')
    <section class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <x-back-button href="{{ route('convocatorias.show', $convocatoria) }}" label="Volver a la convocatoria" />

        <div class="mt-4">
            <h1 class="text-2xl font-bold text-gray-900">Documentación para postular</h1>
            <p class="mt-1 text-sm text-gray-600">
                {{ $convocatoria->titulo }} · {{ $convocatoria->tipo_personal }} · Concurso {{ strtolower($convocatoria->tipo_concurso) }}
            </p>
            <p class="mt-1 text-xs text-gray-500">
                Adjunta un archivo PDF por cada requisito (máximo 10 MB por archivo).
            </p>
        </div>

        <form action="{{ route('postulaciones.store', $convocatoria) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5 rounded-lg border border-gray-200 bg-white p-6">
            @csrf

            @if ($errors->any())
                <div role="alert" class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <section class="space-y-3">
                <h2 class="text-base font-bold text-gray-900">Documentos solicitados</h2>

                <div class="space-y-3">
                    @foreach ($documentos as $key => $documento)
                        <div class="rounded-lg border border-gray-200 p-4">
                            <label for="documento_{{ $key }}" class="block text-sm font-semibold text-gray-900">
                                {{ $documento['label'] }} <span class="text-red-700">*</span>
                            </label>
                            <p class="mt-1 text-xs text-gray-600">{{ $documento['description'] }}</p>
                            <input
                                id="documento_{{ $key }}"
                                type="file"
                                name="documentos[{{ $key }}]"
                                accept="application/pdf,.pdf"
                                required
                                class="mt-3 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700"
                            >
                            @foreach ($errors->get('documentos.'.$key) as $error)
                                <p class="mt-1 text-xs text-red-700">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </section>

            <div>
                <label for="informacion" class="mb-1 block text-sm font-semibold text-gray-700">
                    Información adicional (opcional)
                </label>
                <textarea
                    id="informacion"
                    name="informacion"
                    rows="4"
                    maxlength="10000"
                    placeholder="Agrega información que quieras compartir con el comité."
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-rose-800 focus:outline-none"
                >{{ old('informacion') }}</textarea>
            </div>

            <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                <a
                    href="{{ route('convocatorias.show', $convocatoria) }}"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50"
                >
                    Cancelar
                </a>
                <button
                    type="submit"
                    class="rounded-lg bg-rose-900 px-5 py-2 text-sm font-semibold text-white hover:bg-rose-800"
                >
                    Enviar documentación
                </button>
            </div>
        </form>
    </section>
@endsection