@extends('layouts.app')

@section('title', 'Detalle de notificación - Sistema de Convocatorias')

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <a href="{{ route('notifications.index') }}"
            class="mb-5 inline-flex items-center gap-2 text-sm font-semibold text-rose-900 hover:underline">
            <span aria-hidden="true">&larr;</span>
            Volver a notificaciones
        </a>

        <article class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <header class="border-b border-gray-100 bg-gray-50 px-6 py-5">
                <h1 class="text-2xl font-bold text-gray-900">
                    {{ data_get($notification->data, 'titulo', 'Notificación') }}
                </h1>
                <time datetime="{{ $notification->created_at->toIso8601String() }}"
                    class="mt-2 block text-sm text-gray-500">
                    Recibida el {{ $notification->created_at->format('d/m/Y \a \l\a\s H:i') }}
                </time>
            </header>

            <div class="space-y-5 p-6">
                <div>
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Detalles</h2>
                    <p class="mt-2 leading-relaxed text-gray-800">
                        {{ data_get($notification->data, 'mensaje', 'No hay más detalles para esta notificación.') }}
                    </p>
                </div>

                @if ($relatedUrl = data_get($notification->data, 'url'))
                    <a href="{{ $relatedUrl }}"
                        class="inline-flex items-center justify-center rounded-lg bg-rose-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-800">
                        Ver información relacionada
                    </a>
                @endif
            </div>
        </article>
    </section>
@endsection
