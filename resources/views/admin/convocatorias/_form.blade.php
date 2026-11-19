@php
    $details = $convocatoria?->detalles_especificos ?? [];
    $isEditing = $convocatoria !== null;
    $tipoPersonal = old('tipo_personal', $tipoPersonal);
    $tipoConcurso = old('tipo_concurso', $tipoConcurso);
    $selectedTeachingPositions = (array) old('detalles.plazas_seleccionadas', $details['plazas_seleccionadas'] ?? []);
    $reservedPlazaCodes = $reservedPlazaCodes ?? [];
    $controlClasses = 'w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-900';
@endphp

<form action="{{ $action }}" method="POST" data-convocatoria-form class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    @if ($errors->any())
        <div role="alert" class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <div>
        <label for="titulo" class="mb-1 block text-sm font-semibold text-gray-700">Título</label>
        <input id="titulo" name="titulo" value="{{ old('titulo', $convocatoria?->titulo ?? '') }}" required maxlength="255"
            class="{{ $controlClasses }}">
    </div>

    @if ($isEditing)
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="tipo_personal" class="mb-1 block text-sm font-semibold text-gray-700">Tipo de personal</label>
                <select id="tipo_personal" name="tipo_personal" data-convocatoria-type required class="{{ $controlClasses }}">
                    @foreach (['Docente', 'No docente'] as $type)
                        <option value="{{ $type }}" @selected($tipoPersonal === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="tipo_concurso" class="mb-1 block text-sm font-semibold text-gray-700">Tipo de convocatoria</label>
                <select id="tipo_concurso" name="tipo_concurso" data-convocatoria-type required class="{{ $controlClasses }}">
                    @foreach (['Abierta', 'Cerrada'] as $type)
                        <option value="{{ $type }}" @selected($tipoConcurso === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @else
        <input type="hidden" name="tipo_personal" value="{{ $tipoPersonal }}">
        <input type="hidden" name="tipo_concurso" value="{{ $tipoConcurso }}">
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label for="fecha_inicio" class="mb-1 block text-sm font-semibold text-gray-700">Inicio de convocatoria o registro</label>
            <input id="fecha_inicio" type="date" name="fecha_inicio"
                value="{{ old('fecha_inicio', $convocatoria?->fecha_inicio?->format('Y-m-d') ?? '') }}" required
                class="{{ $controlClasses }}">
        </div>
        <div>
            <label for="fecha_fin" class="mb-1 block text-sm font-semibold text-gray-700">Cierre de convocatoria o registro</label>
            <input id="fecha_fin" type="date" name="fecha_fin"
                value="{{ old('fecha_fin', $convocatoria?->fecha_fin?->format('Y-m-d') ?? '') }}" required
                class="{{ $controlClasses }}">
        </div>
        <div>
            <label for="estatus" class="mb-1 block text-sm font-semibold text-gray-700">Estatus</label>
            <select id="estatus" name="estatus" required class="{{ $controlClasses }}">
                @foreach (['Activa', 'Borrador', 'Cerrada'] as $status)
                    <option value="{{ $status }}" @selected(old('estatus', $convocatoria?->estatus ?? 'Activa') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <fieldset data-convocatoria-details data-tipo-personal="Docente" data-tipo-concurso="*"
        @if ($tipoPersonal !== 'Docente') disabled hidden @endif
        class="space-y-4 rounded-xl border border-rose-100 bg-rose-50/40 p-4 sm:p-5">
        <legend class="px-2 text-base font-semibold text-gray-900">Plazas docentes por jornada</legend>
        <p class="text-sm leading-6 text-gray-600">Selecciona una plaza disponibles para esta convocatoria.</p>
        <details class="overflow-hidden rounded-xl border border-rose-200 bg-white"
            @if ($errors->has('detalles.plazas_seleccionadas') || $selectedTeachingPositions !== []) open @endif>
            <summary class="cursor-pointer px-4 py-3 text-sm font-semibold text-rose-950 transition hover:bg-rose-50">
                Lista de plazas a seleccionar
                <span class="mt-1 block text-xs font-normal text-gray-500">Las plazas asignadas a convocatorias activas o ocupadas, se deshabilitan hasta que se cierren, expiren o desocupen.</span>
            </summary>
            <div class="max-h-80 overflow-y-auto overscroll-contain border-t border-gray-100 p-3">
                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3">


                    @foreach (\App\Models\Convocatoria::TEACHING_POSITIONS as $code => $description)
                        @php
                        $isReserved = in_array($code, $reservedPlazaCodes, true);
                        $isOccupied = in_array($code, $occupiedPlazaCodes ?? [], true);
                        $isDisabled = $isReserved || $isOccupied;
                        @endphp
                        
                        <label @class([
                            'flex items-start gap-3 rounded-xl border p-3 transition',
                            'cursor-pointer border-gray-200 bg-white hover:border-rose-300 hover:bg-rose-50/50' => ! $isDisabled,
                            'cursor-not-allowed border-gray-100 bg-gray-50 opacity-70' => $isDisabled,
                        ])>
                            <input type="radio" name="detalles[plazas_seleccionadas][]" value="{{ $code }}"
                                @checked(! $isDisabled && in_array($code, $selectedTeachingPositions, true))
                                @disabled($isDisabled)
                                class="mt-1 h-4 w-4 rounded border-gray-300 text-rose-900 focus:ring-2 focus:ring-rose-900">
                            <span class="min-w-0">
                                <span @class([
                                    'block font-mono text-sm font-bold',
                                    'text-rose-900' => ! $isDisabled,
                                    'text-gray-500' => $isDisabled,
                                ])>{{ $code }}</span>
                                <span class="mt-0.5 block text-xs leading-5 text-gray-600">{{ $description }}</span>
                                @if ($isReserved)
                                    <span class="mt-1 block text-xs font-semibold text-amber-800">Ya asignada a otra convocatoria activa</span>
                                @elseif ($isOccupied)
                                    <span class="mt-1 block text-xs font-semibold text-rose-800">Plaza ocupada (no disponible)</span>
                                @endif
                            </span>
                        </label>
                    @endforeach

                    
                    
                </div>
            </div>
        </details>
        @error('detalles.plazas_seleccionadas')
            <p role="alert" class="text-sm font-medium text-red-700">{{ $message }}</p>
        @enderror
    </fieldset>

    <fieldset data-convocatoria-details data-tipo-personal="Docente" data-tipo-concurso="Abierta"
        @if ($tipoPersonal !== 'Docente' || $tipoConcurso !== 'Abierta') disabled hidden @endif
        class="space-y-4 rounded-lg border border-gray-200 p-4">
        <legend class="px-2 text-base font-semibold text-gray-900">Bases para concurso docente abierto</legend>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="detalles_categoria_nivel_docente_abierto" class="mb-1 block text-sm font-semibold text-gray-700">Categoría o nivel</label>
                <input id="detalles_categoria_nivel_docente_abierto" name="detalles[categoria_nivel]" value="{{ old('detalles.categoria_nivel', $details['categoria_nivel'] ?? '') }}" required maxlength="150" class="{{ $controlClasses }}">
            </div>
            <div>
                <label for="detalles_plazas_docente_abierto" class="mb-1 block text-sm font-semibold text-gray-700">Plazas disponibles</label>
                <input id="detalles_plazas_docente_abierto" type="number" name="detalles[plazas_disponibles]" value="{{ old('detalles.plazas_disponibles', $details['plazas_disponibles'] ?? '') }}" min="1" step="1" required class="{{ $controlClasses }}">
            </div>
            <div>
                <label for="detalles_jornada_docente_abierto" class="mb-1 block text-sm font-semibold text-gray-700">Jornada y horario</label>
                <input id="detalles_jornada_docente_abierto" name="detalles[jornada_horario]" value="{{ old('detalles.jornada_horario', $details['jornada_horario'] ?? '') }}" required maxlength="150" class="{{ $controlClasses }}">
            </div>
            <div>
                <label for="detalles_salario_docente_abierto" class="mb-1 block text-sm font-semibold text-gray-700">Salario mensual (MXN)</label>
                <input id="detalles_salario_docente_abierto" type="number" name="detalles[salario]" value="{{ old('detalles.salario', $details['salario'] ?? '') }}" min="0" step="0.01" required class="{{ $controlClasses }}">
            </div>
        </div>
        <div>
            <label for="detalles_area_materia" class="mb-1 block text-sm font-semibold text-gray-700">Área o materia</label>
            <input id="detalles_area_materia" name="detalles[area_materia]" value="{{ old('detalles.area_materia', $details['area_materia'] ?? '') }}" required maxlength="255" class="{{ $controlClasses }}">
        </div>
        <div>
            <label for="detalles_perfil_academico" class="mb-1 block text-sm font-semibold text-gray-700">Perfil académico requerido</label>
            <textarea id="detalles_perfil_academico" name="detalles[perfil_academico]" rows="3" maxlength="5000" required class="{{ $controlClasses }}">{{ old('detalles.perfil_academico', $details['perfil_academico'] ?? '') }}</textarea>
        </div>
        <fieldset class="space-y-2">
            <legend class="mb-1 text-sm font-semibold text-gray-700">Pruebas de evaluación</legend>
            @foreach (\App\Models\Convocatoria::EVALUATION_TESTS as $evaluation)
                <label class="flex items-start gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="detalles[pruebas_evaluacion][]" value="{{ $evaluation }}"
                        @checked(in_array($evaluation, (array) old('detalles.pruebas_evaluacion', $details['pruebas_evaluacion'] ?? []), true))>
                    <span>{{ $evaluation }}</span>
                </label>
            @endforeach
        </fieldset>
        <div>
            <label for="detalles_documentacion_obligatoria" class="mb-1 block text-sm font-semibold text-gray-700">Documentación obligatoria</label>
            <textarea id="detalles_documentacion_obligatoria" name="detalles[documentacion_obligatoria]" rows="3" maxlength="5000" required class="{{ $controlClasses }}">{{ old('detalles.documentacion_obligatoria', $details['documentacion_obligatoria'] ?? '') }}</textarea>
        </div>
        <p class="text-sm text-gray-600">El plazo para recibir documentos no debe exceder 10 días hábiles, conforme al reglamento aplicable.</p>
    </fieldset>

    <fieldset data-convocatoria-details data-tipo-personal="Docente" data-tipo-concurso="Cerrada"
        @if ($tipoPersonal !== 'Docente' || $tipoConcurso !== 'Cerrada') disabled hidden @endif
        class="space-y-4 rounded-lg border border-gray-200 p-4">
        <legend class="px-2 text-base font-semibold text-gray-900">Bases para concurso docente cerrado</legend>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="detalles_categoria_actual" class="mb-1 block text-sm font-semibold text-gray-700">Categoría actual</label>
                <input id="detalles_categoria_actual" name="detalles[categoria_actual]" value="{{ old('detalles.categoria_actual', $details['categoria_actual'] ?? '') }}" required maxlength="150" class="{{ $controlClasses }}">
            </div>
            <div>
                <label for="detalles_categoria_aspirada" class="mb-1 block text-sm font-semibold text-gray-700">Categoría aspirada</label>
                <input id="detalles_categoria_aspirada" name="detalles[categoria_aspirada]" value="{{ old('detalles.categoria_aspirada', $details['categoria_aspirada'] ?? '') }}" required maxlength="150" class="{{ $controlClasses }}">
            </div>
            <div>
                <label for="detalles_fecha_evaluacion" class="mb-1 block text-sm font-semibold text-gray-700">Fecha de evaluación</label>
                <input id="detalles_fecha_evaluacion" type="date" name="detalles[fecha_evaluacion]" value="{{ old('detalles.fecha_evaluacion', $details['fecha_evaluacion'] ?? '') }}" required class="{{ $controlClasses }}">
            </div>
            <div>
                <label for="detalles_antiguedad_docente" class="mb-1 block text-sm font-semibold text-gray-700">Antigüedad mínima (meses)</label>
                <input id="detalles_antiguedad_docente" type="number" name="detalles[antiguedad_minima_meses]" value="{{ old('detalles.antiguedad_minima_meses', $details['antiguedad_minima_meses'] ?? 12) }}" min="12" step="1" required class="{{ $controlClasses }}">
            </div>
        </div>
        <div>
            <label for="detalles_constancias_merito" class="mb-1 block text-sm font-semibold text-gray-700">Constancias de mérito</label>
            <textarea id="detalles_constancias_merito" name="detalles[constancias_merito]" rows="3" maxlength="5000" required class="{{ $controlClasses }}">{{ old('detalles.constancias_merito', $details['constancias_merito'] ?? '') }}</textarea>
        </div>
        <div>
            <label for="detalles_comision_dictaminadora" class="mb-1 block text-sm font-semibold text-gray-700">Comisión dictaminadora (nombre o enlace)</label>
            <input id="detalles_comision_dictaminadora" name="detalles[comision_dictaminadora]" value="{{ old('detalles.comision_dictaminadora', $details['comision_dictaminadora'] ?? '') }}" required maxlength="2000" class="{{ $controlClasses }}">
        </div>
    </fieldset>

    <fieldset data-convocatoria-details data-tipo-personal="No docente" data-tipo-concurso="Abierta"
        @if ($tipoPersonal !== 'No docente' || $tipoConcurso !== 'Abierta') disabled hidden @endif
        class="space-y-4 rounded-lg border border-gray-200 p-4">
        <legend class="px-2 text-base font-semibold text-gray-900">Bases para concurso abierto de personal no docente</legend>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="detalles_grupo_puesto_abierto" class="mb-1 block text-sm font-semibold text-gray-700">Grupo del puesto</label>
                <input id="detalles_grupo_puesto_abierto" name="detalles[grupo_puesto]" value="{{ old('detalles.grupo_puesto', $details['grupo_puesto'] ?? '') }}" required maxlength="150" class="{{ $controlClasses }}">
            </div>
            <div>
                <label for="detalles_categoria_nivel_no_docente" class="mb-1 block text-sm font-semibold text-gray-700">Categoría o nivel</label>
                <input id="detalles_categoria_nivel_no_docente" name="detalles[categoria_nivel]" value="{{ old('detalles.categoria_nivel', $details['categoria_nivel'] ?? '') }}" required maxlength="150" class="{{ $controlClasses }}">
            </div>
            <div>
                <label for="detalles_plazas_no_docente" class="mb-1 block text-sm font-semibold text-gray-700">Plazas disponibles</label>
                <input id="detalles_plazas_no_docente" type="number" name="detalles[plazas_disponibles]" value="{{ old('detalles.plazas_disponibles', $details['plazas_disponibles'] ?? '') }}" min="1" step="1" required class="{{ $controlClasses }}">
            </div>
            <div>
                <label for="detalles_salario_no_docente" class="mb-1 block text-sm font-semibold text-gray-700">Salario mensual (MXN)</label>
                <input id="detalles_salario_no_docente" type="number" name="detalles[salario]" value="{{ old('detalles.salario', $details['salario'] ?? '') }}" min="0" step="0.01" required class="{{ $controlClasses }}">
            </div>
            <div>
                <label for="detalles_jornada_no_docente" class="mb-1 block text-sm font-semibold text-gray-700">Jornada y horario</label>
                <input id="detalles_jornada_no_docente" name="detalles[jornada_horario]" value="{{ old('detalles.jornada_horario', $details['jornada_horario'] ?? '') }}" required maxlength="150" class="{{ $controlClasses }}">
            </div>
            <div>
                <label for="detalles_adscripcion" class="mb-1 block text-sm font-semibold text-gray-700">Adscripción</label>
                <input id="detalles_adscripcion" name="detalles[adscripcion]" value="{{ old('detalles.adscripcion', $details['adscripcion'] ?? '') }}" required maxlength="255" class="{{ $controlClasses }}">
            </div>
        </div>
        @foreach ([
            'escolaridad_minima' => 'Escolaridad mínima',
            'competencias' => 'Competencias requeridas',
            'requisitos_ingreso' => 'Requisitos institucionales de ingreso',
        ] as $field => $label)
            <div>
                <label for="detalles_{{ $field }}" class="mb-1 block text-sm font-semibold text-gray-700">{{ $label }}</label>
                <textarea id="detalles_{{ $field }}" name="detalles[{{ $field }}]" rows="3" maxlength="5000" required class="{{ $controlClasses }}">{{ old('detalles.'.$field, $details[$field] ?? '') }}</textarea>
            </div>
        @endforeach
    </fieldset>

    <fieldset data-convocatoria-details data-tipo-personal="No docente" data-tipo-concurso="Cerrada"
        @if ($tipoPersonal !== 'No docente' || $tipoConcurso !== 'Cerrada') disabled hidden @endif
        class="space-y-4 rounded-lg border border-gray-200 p-4">
        <legend class="px-2 text-base font-semibold text-gray-900">Bases para concurso cerrado de personal no docente</legend>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="detalles_grupo_puesto_cerrado" class="mb-1 block text-sm font-semibold text-gray-700">Grupo del puesto</label>
                <input id="detalles_grupo_puesto_cerrado" name="detalles[grupo_puesto]" value="{{ old('detalles.grupo_puesto', $details['grupo_puesto'] ?? '') }}" required maxlength="150" class="{{ $controlClasses }}">
            </div>
            <div>
                <label for="detalles_categoria_vacante" class="mb-1 block text-sm font-semibold text-gray-700">Categoría de la vacante</label>
                <input id="detalles_categoria_vacante" name="detalles[categoria_vacante]" value="{{ old('detalles.categoria_vacante', $details['categoria_vacante'] ?? '') }}" required maxlength="150" class="{{ $controlClasses }}">
            </div>
            <div>
                <label for="detalles_fecha_aplicacion_factores" class="mb-1 block text-sm font-semibold text-gray-700">Fecha de aplicación de factores</label>
                <input id="detalles_fecha_aplicacion_factores" type="date" name="detalles[fecha_aplicacion_factores]" value="{{ old('detalles.fecha_aplicacion_factores', $details['fecha_aplicacion_factores'] ?? '') }}" required class="{{ $controlClasses }}">
            </div>
            <div>
                <label for="detalles_antiguedad_no_docente" class="mb-1 block text-sm font-semibold text-gray-700">Antigüedad mínima (meses)</label>
                <input id="detalles_antiguedad_no_docente" type="number" name="detalles[antiguedad_minima_meses]" value="{{ old('detalles.antiguedad_minima_meses', $details['antiguedad_minima_meses'] ?? 6) }}" min="6" step="1" required class="{{ $controlClasses }}">
            </div>
        </div>
        @foreach ([
            'factor_aptitud' => 'Factor de aptitud',
            'factor_conocimientos' => 'Factor de conocimientos',
            'factor_disciplina' => 'Factor de disciplina',
            'factor_puntualidad' => 'Factor de puntualidad',
        ] as $field => $label)
            <div>
                <label for="detalles_{{ $field }}" class="mb-1 block text-sm font-semibold text-gray-700">{{ $label }}</label>
                <textarea id="detalles_{{ $field }}" name="detalles[{{ $field }}]" rows="3" maxlength="5000" required class="{{ $controlClasses }}">{{ old('detalles.'.$field, $details[$field] ?? '') }}</textarea>
            </div>
        @endforeach
    </fieldset>

    <div>
        <label for="requisitos_especificos" class="mb-1 block text-sm font-semibold text-gray-700">Información o requisitos adicionales (opcional)</label>
        <textarea id="requisitos_especificos" name="requisitos_especificos" rows="4" maxlength="10000" class="{{ $controlClasses }}">{{ old('requisitos_especificos', $convocatoria?->requisitos_especificos ?? '') }}</textarea>
    </div>

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a href="{{ route('admin.convocatorias.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50">Cancelar</a>
        <button type="submit" class="rounded-lg bg-rose-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-rose-800">{{ $submitLabel }}</button>
    </div>
</form>
