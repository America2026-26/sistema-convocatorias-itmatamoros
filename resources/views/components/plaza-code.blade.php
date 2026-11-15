@props([
    'code',
    'status' => 'none',
])

@php
    $plazaStatuses = [
        'own' => [
            'label' => 'Tu plaza',
            'classes' => 'border-violet-300 bg-violet-100 text-violet-950 ring-1 ring-violet-200',
        ],
        'upcoming' => [
            'label' => 'Próxima disponibilidad',
            'classes' => 'border-amber-300 bg-amber-100 text-amber-950 ring-1 ring-amber-200',
        ],
        'occupied' => [
            'label' => 'Ocupada',
            'classes' => 'border-rose-300 bg-rose-100 text-rose-900 ring-1 ring-rose-200',
        ],
        'available' => [
            'label' => 'Disponible en convocatoria',
            'classes' => 'border-emerald-300 bg-emerald-100 text-emerald-900 ring-1 ring-emerald-200',
        ],
        'none' => [
            'label' => 'Sin asignación ni convocatoria',
            'classes' => 'border-slate-200 bg-slate-50 text-slate-700',
        ],
    ];

    $status = array_key_exists($status, $plazaStatuses) ? $status : 'none';
    $plazaStatus = $plazaStatuses[$status];
    $focusRingClass = $status === 'available' ? 'focus:ring-emerald-500' : 'focus:ring-amber-500';
@endphp

@if (in_array($status, ['upcoming', 'available'], true))
    <a
        href="{{ route('home', ['plaza' => $code]) }}#convocatorias"
        {{ $attributes->merge([
            'class' => 'inline-flex cursor-pointer items-center justify-center rounded-lg border px-2.5 py-1 font-mono text-sm transition hover:brightness-95 focus:outline-none focus:ring-2 '.$focusRingClass.' focus:ring-offset-2 '.$plazaStatus['classes'],
        ]) }}
        data-plaza-code="{{ $code }}"
        data-plaza-status="{{ $status }}"
        title="{{ $plazaStatus['label'] }}. Ver convocatorias relacionadas."
        aria-label="Plaza {{ $code }}: {{ $plazaStatus['label'] }}"
    >
        {{ $code }}
    </a>
@else
    <span
        {{ $attributes->merge([
            'class' => 'inline-flex items-center justify-center rounded-lg border px-2.5 py-1 font-mono text-sm '.$plazaStatus['classes'],
        ]) }}
        data-plaza-code="{{ $code }}"
        data-plaza-status="{{ $status }}"
        title="{{ $plazaStatus['label'] }}"
        aria-label="Plaza {{ $code }}: {{ $plazaStatus['label'] }}"
    >
        {{ $code }}
    </span>
@endif