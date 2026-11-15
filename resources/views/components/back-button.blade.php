@props([
    'href',
    'label' => 'Volver',
])

<a
    href="{{ $href }}"
    aria-label="{{ $label }}"
    title="{{ $label }}"
    {{ $attributes->merge([
        'class' => 'inline-flex size-10 shrink-0 items-center justify-center rounded-full border border-gray-300 bg-white text-gray-700 shadow-sm transition hover:border-rose-300 hover:bg-rose-50 hover:text-rose-900 focus:outline-none focus:ring-2 focus:ring-rose-700 focus:ring-offset-2',
    ]) }}
>
    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7M3 12h18" />
    </svg>
    <span class="sr-only">{{ $label }}</span>
</a>
