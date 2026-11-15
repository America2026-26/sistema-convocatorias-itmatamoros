@extends('layouts.app')

@section('title', 'Crear cuenta - Sistema de Convocatorias')

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center gap-3">
            <x-back-button href="{{ route('admin.users.index') }}" label="Volver a cuentas" />
            <h1 class="text-2xl font-bold text-gray-900">Crear cuenta</h1>
        </div>
        @include('admin.users._form', [
            'action' => route('admin.users.store'),
            'method' => 'POST',
            'submitLabel' => 'Crear cuenta',
        ])
    </section>
@endsection
