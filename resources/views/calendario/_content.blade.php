<div class="grid gap-6 lg:grid-cols-[minmax(0,1.05fr)_minmax(0,0.95fr)]">
    <section aria-label="Calendario mensual" class="rounded-2xl border border-gray-200 bg-gray-50/50 p-4 sm:p-5">
        <div class="mb-5 flex items-center justify-between gap-3">
            <a href="{{ route('calendario.institucional', ['mes' => $mesAnterior]) }}"
                data-calendar-month-link
                aria-label="Mes anterior"
                class="inline-flex size-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-700 transition hover:bg-gray-100">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                    <path d="m15 18-6-6 6-6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                </svg>
            </a>
            <h2 data-calendar-month="{{ $mesActual->format('Y-m') }}" class="text-center text-lg font-semibold text-gray-900">
                {{ ucfirst($mesActual->copy()->locale('es')->translatedFormat('F Y')) }}
            </h2>
            <a href="{{ route('calendario.institucional', ['mes' => $mesSiguiente]) }}"
                data-calendar-month-link
                aria-label="Mes siguiente"
                class="inline-flex size-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-700 transition hover:bg-gray-100">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                    <path d="m9 18 6-6-6-6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-7 border-b border-gray-200 pb-2 text-center text-xs font-semibold text-gray-500">
            @foreach (['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $diaSemana)
                <span>{{ $diaSemana }}</span>
            @endforeach
        </div>

        <div class="mt-px grid grid-cols-7 gap-px overflow-hidden rounded-b-xl bg-gray-200">
            @foreach ($diasCalendario as $dia)
                @php
                    $fechaDia = $dia->toDateString();
                    $esHoy = $dia->isToday();
                    $esMesActual = $dia->format('Y-m') === $mesActual->format('Y-m');
                    $eventosDelDia = $eventosPorDia->get($fechaDia, collect());
                @endphp
                <button type="button"
                    data-calendar-date="{{ $fechaDia }}"
                    data-date-label="{{ ucfirst($dia->copy()->locale('es')->translatedFormat('l d \d\e F, Y')) }}"
                    aria-label="Ver eventos del {{ $dia->copy()->locale('es')->translatedFormat('d \d\e F, Y') }}"
                    aria-haspopup="dialog"
                    aria-controls="calendar-event-dialog"
                    @if ($esHoy) aria-current="date" @endif
                    class="flex min-h-16 w-full flex-col items-center gap-1 p-1.5 text-sm transition hover:bg-rose-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-rose-700 sm:min-h-20 sm:p-2 {{ $esHoy ? 'bg-amber-100/80' : ($esMesActual ? 'bg-white' : 'bg-gray-50') }}">
                    <span class="inline-flex size-8 items-center justify-center rounded-full font-medium {{ $esHoy ? 'bg-amber-600 text-white' : ($esMesActual ? 'text-gray-700' : 'text-gray-400') }}">
                        {{ $dia->day }}
                    </span>
                    @if ($eventosDelDia->isNotEmpty())
                        <span class="size-1.5 rounded-full bg-rose-800" aria-hidden="true"></span>
                        <span class="sr-only">{{ $eventosDelDia->count() }} evento(s)</span>
                    @endif
                </button>
            @endforeach
        </div>

        <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2 text-xs text-gray-600">
            <span class="inline-flex items-center gap-2">
                <span class="size-3 rounded-sm bg-amber-100"></span>
                Día actual
            </span>
            <span class="inline-flex items-center gap-2">
                <span class="size-2 rounded-full bg-rose-800"></span>
                Hay eventos
            </span>
        </div>
    </section>

    <section aria-labelledby="eventos-heading" class="space-y-4">
        <div>
            <h2 id="eventos-heading" class="text-lg font-semibold text-gray-900">Próximas fechas</h2>
            <p class="mt-1 text-sm text-gray-500">Los eventos anteriores a hoy ya no aparecen en esta lista.</p>
        </div>

        @if ($puedeAdministrarEventos)
            <form method="POST" action="{{ route('calendario.eventos.store') }}" data-calendar-form
                class="space-y-3 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                @csrf
                <input type="hidden" name="mes" value="{{ $mesActual->format('Y-m') }}">
                <h3 class="text-sm font-semibold text-gray-900">Agregar evento</h3>
                <label class="block text-sm font-medium text-gray-700">
                    Nombre del evento
                    <input name="titulo" type="text" maxlength="150" required value="{{ old('titulo') }}"
                        class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-700/20">
                </label>
                <label class="block text-sm font-medium text-gray-700">
                    Fecha
                    <input name="fecha" type="date" min="{{ $hoy->toDateString() }}" required
                        value="{{ old('fecha', $hoy->toDateString()) }}"
                        class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-700/20">
                </label>
                <label class="block text-sm font-medium text-gray-700">
                    Información adicional
                    <textarea name="descripcion" rows="2" maxlength="2000"
                        class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-700/20">{{ old('descripcion') }}</textarea>
                </label>
                <button type="submit"
                    class="inline-flex items-center rounded-lg bg-rose-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-800 focus:outline-none focus:ring-2 focus:ring-rose-700 focus:ring-offset-2">
                    Guardar evento
                </button>
            </form>
        @endif

        <div data-upcoming-events class="space-y-3">
            @forelse ($eventos as $evento)
                @php($esHoy = $evento->fecha->isToday())
                <article data-event-date-state="{{ $esHoy ? 'today' : 'upcoming' }}"
                    class="rounded-2xl border p-4 {{ $esHoy ? 'border-amber-200 bg-amber-50/60' : 'border-gray-200 bg-gray-50' }}">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <time datetime="{{ $evento->fecha->toDateString() }}"
                                class="text-xs font-semibold uppercase tracking-wide {{ $esHoy ? 'text-amber-800' : 'text-gray-500' }}">
                                {{ $evento->fecha->copy()->locale('es')->translatedFormat('d \d\e F, Y') }}
                            </time>
                            <h3 class="mt-1 break-words font-semibold text-gray-900">{{ $evento->titulo }}</h3>
                            @if ($evento->descripcion)
                                <p class="mt-1 whitespace-pre-line text-sm text-gray-600">{{ $evento->descripcion }}</p>
                            @endif
                        </div>
                        <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold {{ $esHoy ? 'bg-amber-100 text-amber-800' : 'bg-gray-200 text-gray-700' }}">
                            {{ $esHoy ? 'Hoy' : 'Próximo' }}
                        </span>
                    </div>

                    @if ($puedeAdministrarEventos)
                        <details class="mt-4 border-t border-gray-200 pt-3">
                            <summary class="cursor-pointer text-sm font-semibold text-rose-900">Editar evento</summary>
                            <form method="POST" action="{{ route('calendario.eventos.update', $evento) }}"
                                data-calendar-form class="mt-3 space-y-3">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="mes" value="{{ $mesActual->format('Y-m') }}">
                                <label class="block text-sm font-medium text-gray-700">
                                    Nombre del evento
                                    <input name="titulo" type="text" maxlength="150" required value="{{ $evento->titulo }}"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-700/20">
                                </label>
                                <label class="block text-sm font-medium text-gray-700">
                                    Fecha
                                    <input name="fecha" type="date" min="{{ $hoy->toDateString() }}" required
                                        value="{{ $evento->fecha->toDateString() }}"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-700/20">
                                </label>
                                <label class="block text-sm font-medium text-gray-700">
                                    Información adicional
                                    <textarea name="descripcion" rows="2" maxlength="2000"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-700/20">{{ $evento->descripcion }}</textarea>
                                </label>
                                <button type="submit"
                                    class="rounded-lg bg-rose-900 px-3 py-2 text-sm font-semibold text-white transition hover:bg-rose-800">
                                    Guardar cambios
                                </button>
                            </form>
                            <form method="POST" action="{{ route('calendario.eventos.destroy', $evento) }}"
                                data-calendar-form class="mt-2">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="mes" value="{{ $mesActual->format('Y-m') }}">
                                <button type="submit"
                                    class="rounded-lg px-3 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-50">
                                    Eliminar evento
                                </button>
                            </form>
                        </details>
                    @endif
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-5 py-8 text-center">
                    <p class="font-medium text-gray-700">No hay fechas próximas registradas.</p>
                    <p class="mt-1 text-sm text-gray-500">Los eventos de hoy y futuros aparecerán aquí.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>

<dialog id="calendar-event-dialog" data-calendar-event-dialog
    aria-labelledby="calendar-event-dialog-title"
    class="fixed left-1/2 top-1/2 m-0 w-[min(26rem,calc(100vw-2rem))] max-h-[calc(100dvh-2rem)] max-w-none -translate-x-1/2 -translate-y-1/2 overflow-hidden rounded-2xl border border-gray-200 bg-white p-0 text-gray-900 shadow-2xl backdrop:bg-gray-950/50">
    <div class="flex items-start justify-between gap-3 border-b border-gray-200 p-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-rose-900">Eventos de la fecha</p>
            <h2 id="calendar-event-dialog-title" data-calendar-dialog-date class="mt-1 text-base font-semibold text-gray-900"></h2>
        </div>
        <button type="button" data-calendar-dialog-close aria-label="Cerrar ventana de eventos"
            class="inline-flex size-9 shrink-0 items-center justify-center rounded-full text-gray-500 transition hover:bg-gray-100 hover:text-gray-900">
            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                <path d="m6 6 12 12M18 6 6 18" stroke-linecap="round" stroke-width="2" />
            </svg>
        </button>
    </div>
    <div data-calendar-dialog-events class="flex min-h-52 max-h-[min(22rem,calc(100dvh-8rem))] flex-col justify-center gap-3 overflow-y-auto p-4"></div>
</dialog>

<div hidden aria-hidden="true">
    @foreach ($eventosPorDia as $fecha => $eventosDelDia)
        <template data-calendar-events-for="{{ $fecha }}">
            @foreach ($eventosDelDia as $evento)
                <article class="rounded-xl border border-gray-200 bg-gray-50 p-3">
                    <time datetime="{{ $evento->fecha->toDateString() }}"
                        class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        {{ $evento->fecha->copy()->locale('es')->translatedFormat('d \d\e F, Y') }}
                    </time>
                    <h3 class="mt-1 font-semibold text-gray-900">{{ $evento->titulo }}</h3>
                    @if ($evento->descripcion)
                        <p class="mt-1 whitespace-pre-line text-sm text-gray-600">{{ $evento->descripcion }}</p>
                    @endif
                </article>
            @endforeach
        </template>
    @endforeach
</div>
