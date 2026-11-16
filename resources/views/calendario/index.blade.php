@extends('layouts.app')

@section('title', 'Calendario Institucional')

@section('content')
    <div data-calendar-root class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <x-back-button href="{{ route('dashboard') }}" label="Volver al panel" />

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-8">
            <header
                class="mb-8 flex flex-col gap-4 border-b border-gray-200 pb-6 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-900">Instituto Tecnológico de
                        Matamoros</p>
                    <h1 class="mt-2 text-2xl font-bold text-gray-900 sm:text-3xl">Calendario Institucional</h1>
                    <p class="mt-2 max-w-2xl text-sm text-gray-600">
                        Consulta las próximas fechas y actividades institucionales.
                    </p>
                </div>
                <p class="rounded-full bg-gray-50 px-4 py-2 text-sm text-gray-600">
                    Hoy: <span
                        class="font-semibold text-gray-900">{{ $hoy->copy()->locale('es')->translatedFormat('d \d\e F, Y') }}</span>
                </p>
            </header>

            <div data-calendar-feedback role="{{ $errors->any() ? 'alert' : 'status' }}"
                aria-live="{{ $errors->any() ? 'assertive' : 'polite' }}" @if (!$errors->any() && !session('status'))
                hidden @endif
                class="mb-6 rounded-xl border px-4 py-3 text-sm {{ $errors->any() ? 'border-red-200 bg-red-50 text-red-800' : 'border-emerald-200 bg-emerald-50 text-emerald-800' }}">
                @if (session('status'))
                    {{ session('status') }}
                @elseif ($errors->any())
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div data-calendar-content>
                @include('calendario._content')
            </div>
        </div>
    </div>
@endsection