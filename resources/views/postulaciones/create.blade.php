@extends('layouts.app')

@section('title', 'Postularme - '.$convocatoria->titulo)

@section('content')
    <section class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <a href="{{ route('convocatorias.show', $convocatoria) }}" class="text-sm font-semibold text-rose-900 hover:underline">&larr; Volver a la convocatoria</a>
        <h1 class="mt-4 text-2xl font-bold text-gray-900">Documentación para postular</h1>
        <p class="mt-2 text-gray-600">{{ $convocatoria->titulo }} · {{ $convocatoria->tipo_personal }} · Concurso {{ strtolower($convocatoria->tipo_concurso) }}</p>
        <p class="mt-2 text-sm text-gray-600">Adjunta un archivo PDF por cada requisito. Cada documento puede pesar hasta 10 MB.</p>

        <form action="{{ route('postulaciones.store', $convocatoria) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            @csrf

            @if ($errors->any())
                <div role="alert" class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                    @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            <section>
                <h2 class="text-lg font-bold text-gray-900">Documentos solicitados</h2>
                <div class="mt-4 space-y-4">
                    @foreach ($documentos as $key => $documento)
                        <div class="rounded-lg border border-gray-200 p-4">
                            <label for="documento_{{ $key }}" class="block text-sm font-semibold text-gray-900">
                                {{ $documento['label'] }} <span class="text-red-700">*</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-600">{{ $documento['description'] }}</p>
                            <input id="documento_{{ $key }}" type="file" name="documentos[{{ $key }}]" accept="application/pdf,.pdf" required
                                class="mt-3 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                            @foreach ($errors->get('documentos.'.$key) as $error)
                                <p class="mt-1 text-sm text-red-700">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </section>

            <div>
                <label for="informacion" class="mb-1 block text-sm font-semibold text-gray-700">Información adicional (opcional)</label>
                <textarea id="informacion" name="informacion" rows="4" maxlength="10000" placeholder="Agrega información que quieras compartir con el comité."
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-900">{{ old('informacion') }}</textarea>
            </div>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('convocatorias.show', $convocatoria) }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="rounded-lg bg-rose-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-rose-800">Enviar documentación</button>
            </div>
        </form>
    </section>
@endsection
