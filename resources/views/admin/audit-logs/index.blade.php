@extends('layouts.app')

@section('title', 'Historial del sistema - Sistema de Convocatorias')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Historial del sistema</h1>
            <p class="mt-1 text-sm text-gray-600">Accesos y cambios de cuentas, con usuario responsable y fecha.</p>
        </div>

        <div class="space-y-4">
            @forelse ($logs as $log)
                <article class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-start">
                        <div>
                            <h2 class="font-semibold text-gray-900">{{ str_replace(['.', '_'], [' · ', ' '], ucfirst($log->event)) }}</h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Responsable: {{ $log->actor?->name ?? data_get($log->details, 'actor.name', 'Sistema') }}
                                · Cuenta: {{ $log->user?->name ?? data_get($log->details, 'user.name', 'Cuenta eliminada') }}
                            </p>
                        </div>
                        <time datetime="{{ $log->created_at->toIso8601String() }}" class="shrink-0 text-sm text-gray-500">
                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                        </time>
                    </div>

                    @if (data_get($log->details, 'account') || data_get($log->details, 'before') || data_get($log->details, 'after') || data_get($log->details, 'deleted_user'))
                        <details class="mt-4 border-t border-gray-100 pt-3">
                            <summary class="cursor-pointer text-sm font-semibold text-rose-900">Ver detalle del cambio</summary>
                            <pre class="mt-3 overflow-x-auto rounded-lg bg-gray-50 p-3 text-xs text-gray-700">{{ json_encode(collect($log->details)->except(['actor', 'user'])->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </details>
                    @endif
                </article>
            @empty
                <p class="rounded-xl border border-gray-200 bg-white p-8 text-center text-sm text-gray-500">Todavía no hay eventos registrados.</p>
            @endforelse
        </div>

        <div class="mt-4">{{ $logs->links() }}</div>
    </section>
@endsection
