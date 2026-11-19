<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Convocatoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConvocatoriaController extends Controller
{
    public function index(): View
    {
        return view('admin.convocatorias.index', [
            'convocatorias' => Convocatoria::query()
                ->withCount([
                    'postulaciones',
                    'postulaciones as pendientes_count' => fn($query) => $query
                        ->where('estatus', 'pendiente'),
                ])
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->paginate(15),
        ]);
    }

    public function create(Request $request): View
    {
        $selectedTypes = null;

        if ($request->hasAny(['tipo_concurso', 'tipo_personal'])) {
            $selectedTypes = $request->validate([
                'tipo_concurso' => ['required', Rule::in(['Abierta', 'Cerrada'])],
                'tipo_personal' => ['required', Rule::in(['Docente', 'No docente'])],
            ]);
        }

        return view('admin.convocatorias.create', [
            'selectedTypes' => $selectedTypes,
            'reservedPlazaCodes' => $this->reservedTeachingPlazaCodes(),
            'occupiedPlazaCodes' => $this->occupiedTeachingPlazaCodes(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $actor = $request->user();

        DB::transaction(function () use ($data, $actor): void {
            $convocatoria = Convocatoria::create([
                ...$data,
                'created_by' => $actor->id,
            ]);

            AuditLog::record('convocatoria.created', $actor, null, [
                'convocatoria_id' => $convocatoria->id,
                'after' => $this->auditableAttributes($convocatoria),
            ]);
        });

        return redirect()->route('admin.convocatorias.index')
            ->with('status', 'La convocatoria fue creada.');
    }

    public function edit(Convocatoria $convocatoria): View
    {
        return view('admin.convocatorias.edit', [
            'convocatoria' => $convocatoria,
            'reservedPlazaCodes' => $this->reservedTeachingPlazaCodes($convocatoria),
            'occupiedPlazaCodes' => $this->occupiedTeachingPlazaCodes(),
        ]);
    }

    public function update(Request $request, Convocatoria $convocatoria): RedirectResponse
    {
        $data = $this->validatedData($request, $convocatoria);
        $actor = $request->user();

        DB::transaction(function () use ($data, $convocatoria, $actor): void {
            $convocatoria = Convocatoria::query()->lockForUpdate()->findOrFail($convocatoria->id);
            $before = $this->auditableAttributes($convocatoria);
            $convocatoria->update($data);

            AuditLog::record('convocatoria.updated', $actor, null, [
                'convocatoria_id' => $convocatoria->id,
                'before' => $before,
                'after' => $this->auditableAttributes($convocatoria->refresh()),
            ]);
        });

        return redirect()->route('admin.convocatorias.index')
            ->with('status', 'La convocatoria fue actualizada.');
    }

    public function destroy(Request $request, Convocatoria $convocatoria): RedirectResponse
    {
        DB::transaction(function () use ($request, $convocatoria): void {
            $convocatoria = Convocatoria::query()->lockForUpdate()->findOrFail($convocatoria->id);

            // if ($convocatoria->postulaciones()->exists()) {
            //     throw ValidationException::withMessages([
            //         'convocatoria' => 'No es posible eliminar una convocatoria con postulaciones; puedes cerrarla en su lugar.',
            //     ]);
            // }

            $hasPostulaciones = $convocatoria->postulaciones()->exists();
            $isExpired = $convocatoria->fecha_fin < now ()-> toDateString();
            $canDelete = !$hasPostulaciones && $isExpired;

            if (!$canDelete) {
                throw ValidationException::withMessages([
                    'convocatoria' => 'No es posible eliminar una convocatoria con postulaciones o que aún no haya expirado.',
                ]);
            }

            if ($hasPostulaciones){
                $convocatoria->postulaciones()->delete();
                // throw ValidationException::withMessages([
                //     'convocatoria' => 'No es posible eliminar una convocatoria con postulaciones.',
                // ]);
            }

            AuditLog::record('convocatoria.deleted', $request->user(), null, [
                'convocatoria_id' => $convocatoria->id,
                'deleted' => $this->auditableAttributes($convocatoria),
            ]);

            $convocatoria->delete();
        });

        return redirect()->route('admin.convocatorias.index')
            ->with('status', 'La convocatoria fue eliminada.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?Convocatoria $convocatoria = null): array
    {
        $classification = $request->validate([
            'tipo_concurso' => ['required', Rule::in(['Abierta', 'Cerrada'])],
            'tipo_personal' => ['required', Rule::in(['Docente', 'No docente'])],
        ]);
        $detailRules = $this->detailRules(
            $classification['tipo_personal'],
            $classification['tipo_concurso'],
            $this->reservedTeachingPlazaCodes($convocatoria),
        );
        $allowedDetails = array_unique(array_map(
            static fn(string $attribute): string => explode('.', $attribute)[0],
            array_keys($detailRules),
        ));

        $data = $request->validate(
            [
                'titulo' => ['required', 'string', 'max:255'],
                'fecha_inicio' => ['required', 'date'],
                'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
                'estatus' => ['required', Rule::in(['Activa', 'Borrador', 'Cerrada'])],
                'requisitos_especificos' => ['nullable', 'string', 'max:10000'],
                'detalles' => ['required', 'array:' . implode(',', $allowedDetails)],
                ...array_combine(
                    array_map(static fn(string $attribute): string => 'detalles.' . $attribute, array_keys($detailRules)),
                    array_values($detailRules),
                ),
            ],
            [
                'detalles.plazas_seleccionadas.*.not_in' => 'La plaza seleccionada (:input) ya está asignada a otra convocatoria vigente.',
            ],
        );
        $details = $data['detalles'];
        unset($data['detalles']);

        return [
            ...$data,
            ...$classification,
            'categoria_nivel' => match ([$classification['tipo_personal'], $classification['tipo_concurso']]) {
                ['Docente', 'Abierta'], ['No docente', 'Abierta'] => $details['categoria_nivel'],
                ['Docente', 'Cerrada'] => $details['categoria_aspirada'],
                ['No docente', 'Cerrada'] => $details['categoria_vacante'],
            },
            'plaza_disponible' => (string) ($details['plazas_disponibles'] ?? 'No especificada'),
            'jornada_horario' => $details['jornada_horario'] ?? 'No especificada',
            'salario' => $details['salario'] ?? null,
            'detalles_especificos' => $details,
        ];
    }

    /**
     * @return array<string, array<mixed>>
     */
    private function detailRules(
        string $tipoPersonal,
        string $tipoConcurso,
        array $reservedPlazaCodes,
    ): array {
        return match ([$tipoPersonal, $tipoConcurso]) {
            ['Docente', 'Abierta'] => [
                'categoria_nivel' => ['required', 'string', 'max:150'],
                'plazas_seleccionadas' => ['required', 'array', 'min:1'],
                'plazas_seleccionadas.*' => [
                    'required',
                    'string',
                    'distinct:strict',
                    Rule::in(array_keys(Convocatoria::TEACHING_POSITIONS)),
                    Rule::notIn($reservedPlazaCodes),
                ],
                'plazas_disponibles' => ['required', 'integer', 'min:1', 'max:999999'],
                'jornada_horario' => ['required', 'string', 'max:150'],
                'salario' => ['required', 'numeric', 'min:0', 'max:9999999999'],
                'area_materia' => ['required', 'string', 'max:255'],
                'perfil_academico' => ['required', 'string', 'max:5000'],
                'pruebas_evaluacion' => ['required', 'array', 'min:1'],
                'pruebas_evaluacion.*' => ['required', Rule::in(Convocatoria::EVALUATION_TESTS)],
                'documentacion_obligatoria' => ['required', 'string', 'max:5000'],
            ],
            ['Docente', 'Cerrada'] => [
                'categoria_actual' => ['required', 'string', 'max:150'],
                'categoria_aspirada' => ['required', 'string', 'max:150'],
                'plazas_seleccionadas' => ['required', 'array', 'min:1'],
                'plazas_seleccionadas.*' => [
                    'required',
                    'string',
                    'distinct:strict',
                    Rule::in(array_keys(Convocatoria::TEACHING_POSITIONS)),
                    Rule::notIn($reservedPlazaCodes),
                ],
                'fecha_evaluacion' => ['required', 'date'],
                'antiguedad_minima_meses' => ['required', 'integer', 'min:12', 'max:1200'],
                'constancias_merito' => ['required', 'string', 'max:5000'],
                'comision_dictaminadora' => ['required', 'string', 'max:2000'],
            ],
            ['No docente', 'Abierta'] => [
                'grupo_puesto' => ['required', 'string', 'max:150'],
                'categoria_nivel' => ['required', 'string', 'max:150'],
                'plazas_disponibles' => ['required', 'integer', 'min:1', 'max:999999'],
                'salario' => ['required', 'numeric', 'min:0', 'max:9999999999'],
                'jornada_horario' => ['required', 'string', 'max:150'],
                'adscripcion' => ['required', 'string', 'max:255'],
                'escolaridad_minima' => ['required', 'string', 'max:2000'],
                'competencias' => ['required', 'string', 'max:5000'],
                'requisitos_ingreso' => ['required', 'string', 'max:5000'],
            ],
            ['No docente', 'Cerrada'] => [
                'grupo_puesto' => ['required', 'string', 'max:150'],
                'categoria_vacante' => ['required', 'string', 'max:150'],
                'fecha_aplicacion_factores' => ['required', 'date'],
                'antiguedad_minima_meses' => ['required', 'integer', 'min:6', 'max:1200'],
                'factor_aptitud' => ['required', 'string', 'max:5000'],
                'factor_conocimientos' => ['required', 'string', 'max:5000'],
                'factor_disciplina' => ['required', 'string', 'max:5000'],
                'factor_puntualidad' => ['required', 'string', 'max:5000'],
            ],
        };
    }

    /**
     * @return list<string>
     */
    private function reservedTeachingPlazaCodes(?Convocatoria $excludingConvocatoria = null): array
    {
        return Convocatoria::query()
            ->where('tipo_personal', 'Docente')
            ->where('estatus', 'Activa')
            ->whereDate('fecha_fin', '>=', today())
            ->when(
                $excludingConvocatoria !== null,
                fn($query) => $query->where('id', '<>', $excludingConvocatoria->getKey()),
            )
            ->get(['detalles_especificos'])
            ->flatMap(static function (Convocatoria $convocatoria): array {
                $selectedPlazaCodes = $convocatoria->detalles_especificos['plazas_seleccionadas'] ?? [];

                return \is_array($selectedPlazaCodes) ? $selectedPlazaCodes : [];
            })
            ->filter(static fn(mixed $code): bool => \is_string($code)
                && \array_key_exists($code, Convocatoria::TEACHING_POSITIONS))
            ->unique()
            ->values()
            ->all();
    }

    private function occupiedTeachingPlazaCodes(): array
    {
        // Ajusta 'Plaza', 'estatus' y 'ocupada' según los nombres reales en tu BD
        return \App\Models\User::query()
            ->where('plaza_jornada', '!=', null)
            ->pluck('plaza_jornada') // o el campo que contenga la clave de la plaza (ej. 'E3817')
            ->filter(static fn(mixed $code): bool => \is_string($code) && \array_key_exists($code, Convocatoria::TEACHING_POSITIONS))
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function auditableAttributes(Convocatoria $convocatoria): array
    {
        return $convocatoria->only([
            'titulo',
            'tipo_concurso',
            'tipo_personal',
            'categoria_nivel',
            'plaza_disponible',
            'jornada_horario',
            'salario',
            'fecha_inicio',
            'fecha_fin',
            'estatus',
            'requisitos_especificos',
            'detalles_especificos',
        ]);
    }
}
