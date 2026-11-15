@props([
    'postulacion',
    'documents',
    'documentRoute',
    'heading' => 'Documentación',
    'emptyMessage' => 'No hay documentos para mostrar.',
    'legacyUrl' => null,
])

@php
    $viewerId = 'postulacion-pdf-panel-'.$postulacion->getKey();
    $frameId = 'postulacion-pdf-frame-'.$postulacion->getKey();
@endphp

<section class="mt-6 border-t border-gray-100 pt-5">
    <h2 class="font-bold text-gray-900">{{ $heading }}</h2>

    @if ($documents->isNotEmpty() || $legacyUrl)
        <div class="mt-3 grid min-w-0 gap-4 lg:grid-cols-[minmax(15rem,0.85fr)_minmax(0,2fr)]" data-pdf-viewer>
            <aside class="min-w-0 lg:max-h-[72vh] lg:overflow-y-auto" aria-label="Lista de documentos">
                <h3 class="sr-only">Selecciona un documento</h3>
                <ul class="flex flex-col gap-2">
                    @forelse ($documents as $documento)
                        <li>
                            <button
                                type="button"
                                data-pdf-viewer-toggle
                                aria-controls="{{ $viewerId }}"
                                data-pdf-url="{{ route($documentRoute, [$postulacion, $documento]) }}"
                                data-pdf-title="{{ $documento->nombre }}"
                                aria-expanded="false"
                                aria-label="Mostrar PDF: {{ $documento->nombre }}"
                                class="flex w-full items-center justify-between gap-3 rounded-lg border border-gray-200 bg-white px-3 py-3 text-left text-sm text-gray-700 transition hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-900"
                            >
                                <span class="min-w-0 break-words font-medium">{{ $documento->nombre }}</span>
                                <span class="shrink-0 text-xs font-semibold text-rose-900" data-pdf-toggle-label>Ver PDF</span>
                                <svg data-pdf-toggle-chevron class="size-4 shrink-0 transition-transform" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                    <path d="m5 7.5 5 5 5-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </li>
                    @empty
                        <li>
                            <button
                                type="button"
                                data-pdf-viewer-toggle
                                aria-controls="{{ $viewerId }}"
                                data-pdf-url="{{ $legacyUrl }}"
                                data-pdf-title="Expediente PDF"
                                aria-expanded="false"
                                aria-label="Mostrar expediente PDF"
                                class="flex w-full items-center justify-between gap-3 rounded-lg border border-gray-200 bg-white px-3 py-3 text-left text-sm text-gray-700 transition hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-900"
                            >
                                <span class="font-medium">Expediente PDF</span>
                                <span class="shrink-0 text-xs font-semibold text-rose-900" data-pdf-toggle-label>Ver PDF</span>
                                <svg data-pdf-toggle-chevron class="size-4 shrink-0 transition-transform" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                    <path d="m5 7.5 5 5 5-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </li>
                    @endforelse
                </ul>
            </aside>

            <div class="flex min-h-[18rem] min-w-0 items-center justify-center rounded-xl border border-dashed border-gray-300 bg-gray-50 p-6 text-center" data-pdf-placeholder>
                <p class="max-w-sm text-sm text-gray-600">Selecciona un documento de la lista para mostrarlo aquí.</p>
            </div>

            <div id="{{ $viewerId }}" class="hidden min-w-0 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm" data-pdf-preview>
                <div class="flex flex-col justify-between gap-3 border-b border-gray-100 px-4 py-3 sm:flex-row sm:items-center">
                    <h3 class="min-w-0 break-words text-sm font-semibold text-gray-900" data-pdf-current-title></h3>
                    <div class="flex shrink-0 flex-wrap gap-2">
                        <a
                            href="#"
                            target="_blank"
                            rel="noopener noreferrer"
                            data-pdf-open-new-tab
                            class="rounded-md border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-900"
                        >
                            Abrir en pestaña nueva
                        </a>
                        <button
                            type="button"
                            data-pdf-hide
                            class="rounded-md border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-900"
                        >
                            Ocultar PDF
                        </button>
                        <button
                            type="button"
                            data-pdf-fullscreen="{{ $frameId }}"
                            class="rounded-md bg-rose-900 px-3 py-2 text-xs font-semibold text-white transition hover:bg-rose-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-900 focus-visible:ring-offset-2"
                        >
                            Pantalla completa
                        </button>
                    </div>
                </div>

                <iframe
                    id="{{ $frameId }}"
                    title="Visor de PDF"
                    allowfullscreen
                    class="block h-[68vh] min-h-[30rem] w-full bg-white"
                    data-pdf-frame
                ></iframe>
            </div>
            <p class="hidden text-sm text-red-700 lg:col-start-2" role="status" data-pdf-viewer-status></p>
        </div>
    @else
        <p class="mt-2 text-sm text-gray-500">{{ $emptyMessage }}</p>
    @endif
</section>
