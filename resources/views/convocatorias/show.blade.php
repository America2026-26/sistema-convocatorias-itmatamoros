@extends('layouts.app')

@section('title', $convocatoria->titulo.' - Convocatorias ITM')

@section('content')
    <section class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <x-back-button href="{{ route('home') }}#convocatorias" label="Volver a convocatorias" />

        <article class="mt-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            @if (session('status'))
                <p role="status" class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('status') }}</p>
            @endif
            @if ($errors->has('convocatoria'))
                <p role="alert" class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800">{{ $errors->first('convocatoria') }}</p>
            @endif
            <div class="flex flex-wrap gap-2">
                <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-900">{{ $convocatoria->tipo_personal }}</span>
                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">{{ $convocatoria->estatus }}</span>
            </div>
            <h1 class="mt-4 text-2xl font-bold text-gray-900 sm:text-3xl">{{ $convocatoria->titulo }}</h1>
            <p class="mt-2 text-gray-600">{{ $convocatoria->tipo_concurso }}</p>

            <div class="mt-6">
                @include('convocatorias._details', ['convocatoria' => $convocatoria])
            </div>

            <div class="mt-8 border-t border-gray-100 pt-6">
                @if ($postulacionExistente)
                    <p class="rounded-lg bg-emerald-50 p-4 text-sm text-emerald-800">
                        Ya enviaste una postulación a esta convocatoria.
                        <a href="{{ route('postulaciones.show', $postulacionExistente) }}" class="font-semibold underline">Ver expediente enviado</a>
                    </p>
                @elseif (! $convocatoria->isOpen())
                    <p class="rounded-lg bg-gray-50 p-4 text-sm text-gray-600">Esta convocatoria no está recibiendo postulaciones actualmente.</p>
                @elseif (auth()->check() && auth()->user()->role === \App\Models\User::ROLE_USER)
                    <a href="{{ route('postulaciones.create', $convocatoria) }}" class="inline-flex rounded-lg bg-rose-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-rose-800">Postularme</a>
                @elseif (! auth()->check())
                    <p class="text-sm text-gray-600">
                        <a href="{{ route('login') }}" class="font-semibold text-rose-900 hover:underline">Inicia sesión</a>
                        o <a href="{{ route('register') }}" class="font-semibold text-rose-900 hover:underline">crea una cuenta</a> para postularte.
                    </p>
                @else
                    <p class="text-sm text-gray-600">Las cuentas de dictaminador y administrador no pueden presentar postulaciones.</p>
                @endif
            </div>
        </article>
    </section>
@endsection
