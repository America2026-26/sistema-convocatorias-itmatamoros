@extends('layouts.app')

@section('title', 'Editar cuenta - Sistema de Convocatorias')

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="mb-6 text-2xl font-bold text-gray-900">Editar cuenta: {{ $user->name }}</h1>
        @include('admin.users._form', [
            'action' => route('admin.users.update', $user),
            'method' => 'PUT',
            'submitLabel' => 'Guardar cambios',
        ])
    </section>
@endsection
