@php
    $details = $convocatoria->detalles_especificos ?? [];
    $compact = $compact ?? false;

    $categoryLabel = match ([$convocatoria->tipo_personal, $convocatoria->tipo_concurso]) {
        ['Docente', 'Cerrada'] => 'Categoría aspirada',
        ['No docente', 'Cerrada'] => 'Categoría de la vacante',
        default => 'Categoría o nivel',
    };

    $categoryValue = match ([$convocatoria->tipo_personal, $convocatoria->tipo_concurso]) {
        ['Docente', 'Cerrada'] => $details['categoria_aspirada'] ?? $convocatoria->categoria_nivel,
        ['No docente', 'Cerrada'] => $details['categoria_vacante'] ?? $convocatoria->categoria_nivel,
        default => $convocatoria->categoria_nivel,
    };

    $fields = [
        $categoryLabel => $categoryValue,
        'Plazas disponibles' => $convocatoria->plaza_disponible,
        'Jornada y horario' => $convocatoria->jornada_horario,
        'Salario mensual' => $convocatoria->salario !== null
            ? '$'.number_format((float) $convocatoria->salario, 2).' MXN'
            : 'No especificado',
        'Inicio de convocatoria o registro' => $convocatoria->fecha_inicio->format('d/m/Y'),
        'Cierre de convocatoria o registro' => $convocatoria->fecha_fin->format('d/m/Y'),
    ];

    if ($convocatoria->tipo_personal === 'Docente') {
        $selectedPositions = [];
        foreach ((array) ($details['plazas_seleccionadas'] ?? []) as $selectedPosition) {
            if (is_string($selectedPosition) && isset(\App\Models\Convocatoria::TEACHING_POSITIONS[$selectedPosition])) {
                $selectedPositions[] = $selectedPosition.' — '.\App\Models\Convocatoria::TEACHING_POSITIONS[$selectedPosition];
            }
        }
        if (!empty($selectedPositions)) {
            $fields['Plazas seleccionadas'] = $selectedPositions;
        }
    }

    if (!$compact) {
        $variantFields = match ([$convocatoria->tipo_personal, $convocatoria->tipo_concurso]) {
            ['Docente', 'Abierta'] => [
                'Área o materia' => $details['area_materia'] ?? null,
                'Perfil académico requerido' => $details['perfil_academico'] ?? null,
                'Pruebas de evaluación' => $details['pruebas_evaluacion'] ?? [],
                'Documentación obligatoria adicional' => $details['documentacion_obligatoria'] ?? null,
            ],
            ['Docente', 'Cerrada'] => [
                'Categoría actual' => $details['categoria_actual'] ?? null,
                'Fecha de evaluación' => isset($details['fecha_evaluacion'])
                    ? \Carbon\Carbon::parse($details['fecha_evaluacion'])->format('d/m/Y')
                    : null,
                'Antigüedad mínima' => isset($details['antiguedad_minima_meses'])
                    ? $details['antiguedad_minima_meses'].' meses'
                    : null,
                'Constancias de mérito' => $details['constancias_merito'] ?? null,
                'Comisión dictaminadora' => $details['comision_dictaminadora'] ?? null,
            ],
            ['No docente', 'Abierta'] => [
                'Grupo del puesto' => $details['grupo_puesto'] ?? null,
                'Adscripción' => $details['adscripcion'] ?? null,
                'Escolaridad mínima' => $details['escolaridad_minima'] ?? null,
                'Competencias requeridas' => $details['competencias'] ?? null,
                'Requisitos institucionales de ingreso' => $details['requisitos_ingreso'] ?? null,
            ],
            ['No docente', 'Cerrada'] => [
                'Grupo del puesto' => $details['grupo_puesto'] ?? null,
                'Fecha de aplicación de factores' => isset($details['fecha_aplicacion_factores'])
                    ? \Carbon\Carbon::parse($details['fecha_aplicacion_factores'])->format('d/m/Y')
                    : null,
                'Antigüedad mínima' => isset($details['antiguedad_minima_meses'])
                    ? $details['antiguedad_minima_meses'].' meses'
                    : null,
                'Factor de aptitud' => $details['factor_aptitud'] ?? null,
                'Factor de conocimientos' => $details['factor_conocimientos'] ?? null,
                'Factor de disciplina' => $details['factor_disciplina'] ?? null,
                'Factor de puntualidad' => $details['factor_puntualidad'] ?? null,
            ],
        };
        $fields = [...$fields, ...$variantFields];
    }

    $documentRequirements = $convocatoria->applicantDocumentRequirements();
@endphp

<div class="{{ $compact ? 'space-y-3' : 'space-y-6' }}">
    <dl class="grid grid-cols-1 gap-3 rounded-lg bg-rose-50 p-4 text-sm sm:grid-cols-2">
        @foreach ($fields as $label => $value)
            <div>
                <dt class="text-xs font-semibold text-gray-500">{{ $label }}</dt>
                <dd class="mt-1 whitespace-pre-line text-gray-900">
                    @if (is_array($value))
                        @if ($value !== [])
                            <ul class="list-inside list-disc space-y-1">
                                @foreach ($value as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        @else
                            No especificado
                        @endif
                    @else
                        {{ filled($value) ? $value : 'No especificado' }}
                    @endif
                </dd>
            </div>
        @endforeach
    </dl>

    @if (!$compact)
        <section>
            <h3 class="font-bold text-gray-900">Requisitos e información adicional</h3>
            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-700">
                {{ $convocatoria->requisitos_especificos ?: 'Consulta las bases con el área correspondiente.' }}
            </p>
        </section>

        <section>
            <h3 class="font-bold text-gray-900">Documentos solicitados para postular</h3>
            <ul class="mt-2 space-y-2">
                @foreach ($documentRequirements as $document)
                    <li class="rounded-lg border border-gray-100 p-3">
                        <p class="text-sm font-semibold text-gray-900">{{ $document['label'] }}</p>
                        <p class="mt-1 text-sm text-gray-600">{{ $document['description'] }}</p>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif
</div>