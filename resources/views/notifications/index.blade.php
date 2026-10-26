@extends('layouts.app')

@section('title', 'Notificaciones - Sistema de Convocatorias')

@section('content')
    <section class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Notificaciones</h1>
                <p class="mt-1 text-sm text-gray-600">Avisos de postulaciones y actualizaciones del sistema.</p>
            </div>
            @if (auth()->user()->unreadNotifications()->exists())
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Marcar todas como leídas</button>
                </form>
            @endif
        </div>

        <form action="{{ route('notifications.index') }}" method="GET"
            class="mb-6 grid grid-cols-1 items-end gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-[1fr_1fr_auto_auto]">
            @if ($errors->has('fecha_desde') || $errors->has('fecha_hasta'))
                <p role="alert" class="text-sm text-red-700 sm:col-span-2 lg:col-span-4">
                    Revisa el rango de fechas seleccionado.
                </p>
            @endif
            <div>
                <label for="fecha_desde" class="mb-1 block text-sm font-semibold text-gray-700">Desde</label>
                <input id="fecha_desde" type="date" name="fecha_desde" value="{{ $filters['fecha_desde'] }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-900">
            </div>
            <div>
                <label for="fecha_hasta" class="mb-1 block text-sm font-semibold text-gray-700">Hasta</label>
                <input id="fecha_hasta" type="date" name="fecha_hasta" value="{{ $filters['fecha_hasta'] }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-900">
            </div>
            <button type="submit"
                class="rounded-lg bg-rose-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-800">
                Filtrar fechas
            </button>
            @if ($filters['fecha_desde'] !== '' || $filters['fecha_hasta'] !== '')
                <a href="{{ route('notifications.index') }}"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-center text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    Limpiar
                </a>
            @endif
        </form>

        <div class="space-y-3">
            @forelse ($notifications as $notification)
                <a href="{{ route('notifications.show', $notification->id) }}"
                    class="block rounded-xl border p-5 transition hover:border-rose-300 {{ $notification->read_at ? 'border-gray-200 bg-white' : 'border-rose-200 bg-rose-50' }}">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="font-semibold text-gray-900">{{ data_get($notification->data, 'titulo', 'Notificación') }}</h2>
                            <p class="mt-1 text-sm text-gray-700">{{ data_get($notification->data, 'mensaje', '') }}</p>
                        </div>
                        @if (! $notification->read_at)
                            <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-rose-600" aria-label="No leída"></span>
                        @endif
                    </div>
                    <time datetime="{{ $notification->created_at->toIso8601String() }}" class="mt-3 block text-xs text-gray-500">{{ $notification->created_at->format('d/m/Y H:i') }}</time>
                </a>
            @empty
                <p class="rounded-xl border border-gray-200 bg-white p-8 text-center text-sm text-gray-500">No hay notificaciones para las fechas seleccionadas.</p>
            @endforelse
        </div>
        <div class="mt-4">{{ $notifications->links() }}</div>
    </section>
@endsection
