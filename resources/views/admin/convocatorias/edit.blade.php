@extends('layouts.app')

@section('title', 'Editar convocatoria - Panel del Dictaminador')

@section('content')
    <section class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center gap-3">
            <x-back-button href="{{ route('admin.convocatorias.index') }}" label="Volver a convocatorias" />
            <h1 class="text-2xl font-bold text-gray-900">Editar convocatoria</h1>
        </div>
        @include('admin.convocatorias._form', [
            'action' => route('admin.convocatorias.update', $convocatoria),
            'method' => 'PUT',
            'submitLabel' => 'Guardar cambios',
            'tipoConcurso' => $convocatoria->tipo_concurso,
            'tipoPersonal' => $convocatoria->tipo_personal,
            'reservedPlazaCodes' => $reservedPlazaCodes,
        ])
    </section>
@endsection
