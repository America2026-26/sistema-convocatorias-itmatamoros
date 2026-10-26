@extends('layouts.app')

@section('title', 'Crear convocatoria - Panel del Dictaminador')

@section('content')
    <section class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="mb-6 text-2xl font-bold text-gray-900">Crear convocatoria</h1>
        @include('dictaminador.convocatorias._form', [
            'action' => route('dictaminador.convocatorias.store'),
            'method' => 'POST',
            'submitLabel' => 'Publicar convocatoria',
        ])
    </section>
@endsection
