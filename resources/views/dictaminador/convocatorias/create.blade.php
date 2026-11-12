@extends('layouts.app')

@section('title', 'Crear convocatoria - Panel del Dictaminador')

@section('content')
    <section class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="mb-6 text-2xl font-bold text-gray-900">Crear convocatoria</h1>
        @if ($selectedTypes === null)
            <div class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Paso 1: selecciona el tipo de convocatoria</h2>
                    <p class="mt-1 text-sm text-gray-600">El formulario mostrará los requisitos correspondientes a tu selección.</p>
                </div>

                @if ($errors->any())
                    <div role="alert" class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                        @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                    </div>
                @endif

                <form action="{{ route('dictaminador.convocatorias.create') }}" method="GET" class="space-y-5">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="tipo_concurso" class="mb-1 block text-sm font-semibold text-gray-700">Tipo de convocatoria</label>
                            <select id="tipo_concurso" name="tipo_concurso" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-900">
                                <option value="">Selecciona abierta o cerrada</option>
                                @foreach (['Abierta', 'Cerrada'] as $type)
                                    <option value="{{ $type }}" @selected(old('tipo_concurso') === $type)>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="tipo_personal" class="mb-1 block text-sm font-semibold text-gray-700">Tipo de personal</label>
                            <select id="tipo_personal" name="tipo_personal" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-900">
                                <option value="">Selecciona docente o no docente</option>
                                @foreach (['Docente', 'No docente'] as $type)
                                    <option value="{{ $type }}" @selected(old('tipo_personal') === $type)>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="rounded-lg bg-rose-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-rose-800">Continuar</button>
                </form>
            </div>
        @else
            <div class="mb-5 rounded-lg border border-rose-200 bg-rose-50 p-4 text-sm text-rose-900">
                Paso 2: convocatoria {{ $selectedTypes['tipo_concurso'] }} para personal {{ strtolower($selectedTypes['tipo_personal']) }}.
                <a href="{{ route('dictaminador.convocatorias.create') }}" class="ml-1 font-semibold underline">Cambiar selección</a>
            </div>
            @include('dictaminador.convocatorias._form', [
                'action' => route('dictaminador.convocatorias.store', $selectedTypes),
                'method' => 'POST',
                'submitLabel' => 'Guardar convocatoria',
                'tipoConcurso' => $selectedTypes['tipo_concurso'],
                'tipoPersonal' => $selectedTypes['tipo_personal'],
                'convocatoria' => null,
            ])
        @endif
    </section>
@endsection
