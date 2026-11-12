<?php

namespace Tests\Feature;

use App\Models\Convocatoria;
use App\Models\Postulacion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DictaminadorWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    private const EXPECTED_TEACHING_POSITION_CODES = [
        'E3617', 'E3717', 'E3817',
        'E3615', 'E3715', 'E3815',
        'E3613', 'E3713', 'E3813',
        'E3611', 'E3711', 'E3811',
        'E3609', 'E3709', 'E3809',
        'E3607', 'E3707', 'E3807',
    ];

    public function test_reviewer_can_create_update_and_delete_convocatorias_without_applicants(): void
    {
        $reviewer = User::factory()->create(['role' => User::ROLE_REVIEWER]);
        $this->actingAs($reviewer);

        $this->post(route('dictaminador.convocatorias.store'), $this->convocatoriaData())
            ->assertRedirect(route('dictaminador.convocatorias.index'));

        $convocatoria = Convocatoria::query()->firstOrFail();
        $this->assertSame($reviewer->id, $convocatoria->created_by);
        $this->assertSame('Abierta', $convocatoria->tipo_concurso);
        $this->assertSame('Docente', $convocatoria->tipo_personal);
        $this->assertSame($this->convocatoriaData()['detalles'], $convocatoria->detalles_especificos);
        $this->assertDatabaseHas('audit_logs', [
            'event' => 'convocatoria.created',
            'actor_id' => $reviewer->id,
        ]);

        $editResponse = $this->get(route('dictaminador.convocatorias.edit', $convocatoria))
            ->assertOk()
            ->assertSee('Bases para concurso docente abierto');
        $this->assertSame(
            1,
            preg_match(
                '/<input type="checkbox" name="detalles\[plazas_seleccionadas\]\[\]" value="E3617"\s+checked\b/',
                (string) $editResponse->getContent(),
            ),
        );

        $this->put(route('dictaminador.convocatorias.update', $convocatoria), [
            ...$this->convocatoriaData(),
            'titulo' => 'Convocatoria actualizada',
            'detalles' => [
                ...$this->convocatoriaData()['detalles'],
                'perfil_academico' => 'Doctorado en Matemáticas.',
            ],
        ])->assertRedirect(route('dictaminador.convocatorias.index'));
        $this->assertSame('Convocatoria actualizada', $convocatoria->refresh()->titulo);
        $this->assertSame('Doctorado en Matemáticas.', $convocatoria->detalles_especificos['perfil_academico']);
        $this->assertSame(['E3617', 'E3817'], $convocatoria->detalles_especificos['plazas_seleccionadas']);

        $this->delete(route('dictaminador.convocatorias.destroy', $convocatoria))
            ->assertRedirect(route('dictaminador.convocatorias.index'));
        $this->assertDatabaseMissing('convocatorias', ['id' => $convocatoria->id]);
        $this->assertDatabaseHas('audit_logs', [
            'event' => 'convocatoria.updated',
            'actor_id' => $reviewer->id,
        ]);
        $this->assertDatabaseHas('audit_logs', [
            'event' => 'convocatoria.deleted',
            'actor_id' => $reviewer->id,
        ]);
    }

    public function test_convocatoria_form_and_validation_use_fixed_personnel_and_contest_options(): void
    {
        $reviewer = User::factory()->create(['role' => User::ROLE_REVIEWER]);

        $this->actingAs($reviewer)
            ->get(route('dictaminador.convocatorias.create'))
            ->assertOk()
            ->assertSee('<select id="tipo_personal"', false)
            ->assertSee('<option value="Docente"', false)
            ->assertSee('<option value="No docente"', false)
            ->assertSee('<select id="tipo_concurso"', false)
            ->assertSee('<option value="Abierta"', false)
            ->assertSee('<option value="Cerrada"', false)
            ->assertSee('Continuar')
            ->assertDontSee('Área o materia');

        $this->actingAs($reviewer)
            ->get(route('dictaminador.convocatorias.create', [
                'tipo_personal' => 'Docente',
                'tipo_concurso' => 'Abierta',
            ]))
            ->assertOk()
            ->assertSee('Área o materia')
            ->assertSee('Perfil académico')
            ->assertSee('Documentación obligatoria');

        foreach (['Abierta', 'Cerrada'] as $tipoConcurso) {
            $response = $this->actingAs($reviewer)
                ->get(route('dictaminador.convocatorias.create', [
                    'tipo_personal' => 'Docente',
                    'tipo_concurso' => $tipoConcurso,
                ]))
                ->assertOk()
                ->assertSee('Plazas docentes por jornada');

            $this->assertSame(
                18,
                substr_count((string) $response->getContent(), 'name="detalles[plazas_seleccionadas][]"'),
            );

            foreach (self::EXPECTED_TEACHING_POSITION_CODES as $code) {
                $response->assertSee('value="'.$code.'"', false);
            }
        }

        $this->actingAs($reviewer)
            ->get(route('dictaminador.convocatorias.create', ['tipo_personal' => 'Docente']))
            ->assertSessionHasErrors('tipo_concurso');

        $this->actingAs($reviewer)
            ->post(route('dictaminador.convocatorias.store'), [
                ...$this->convocatoriaData(),
                'tipo_personal' => 'Otro',
            ])
            ->assertSessionHasErrors('tipo_personal');

        $this->assertDatabaseCount('convocatorias', 0);
    }

    #[DataProvider('convocatoriaVariants')]
    public function test_reviewer_can_create_each_convocatoria_variant_with_its_details(
        string $tipoPersonal,
        string $tipoConcurso,
        array $details,
    ): void {
        $reviewer = User::factory()->create(['role' => User::ROLE_REVIEWER]);

        $this->actingAs($reviewer)
            ->post(route('dictaminador.convocatorias.store'), [
                ...$this->convocatoriaData(),
                'tipo_personal' => $tipoPersonal,
                'tipo_concurso' => $tipoConcurso,
                'detalles' => $details,
            ])
            ->assertRedirect(route('dictaminador.convocatorias.index'));

        $convocatoria = Convocatoria::query()->firstOrFail();

        $this->assertSame($tipoPersonal, $convocatoria->tipo_personal);
        $this->assertSame($tipoConcurso, $convocatoria->tipo_concurso);
        $this->assertSame($details, $convocatoria->detalles_especificos);
        $this->assertSame(
            $details['categoria_nivel'] ?? $details['categoria_aspirada'] ?? $details['categoria_vacante'],
            $convocatoria->categoria_nivel,
        );
        $this->assertSame(
            (string) ($details['plazas_disponibles'] ?? 'No especificada'),
            $convocatoria->plaza_disponible,
        );
        $this->assertSame(
            $details['jornada_horario'] ?? 'No especificada',
            $convocatoria->jornada_horario,
        );
    }

    public function test_convocatoria_requires_the_selected_variants_details(): void
    {
        $reviewer = User::factory()->create(['role' => User::ROLE_REVIEWER]);
        $data = $this->convocatoriaData();
        unset($data['detalles']['area_materia']);

        $this->actingAs($reviewer)
            ->post(route('dictaminador.convocatorias.store'), $data)
            ->assertSessionHasErrors('detalles.area_materia');

        $this->assertDatabaseCount('convocatorias', 0);
    }

    public function test_docente_convocatoria_requires_at_least_one_selected_plaza(): void
    {
        $reviewer = User::factory()->create(['role' => User::ROLE_REVIEWER]);
        $data = $this->convocatoriaData();
        unset($data['detalles']['plazas_seleccionadas']);

        $this->actingAs($reviewer)
            ->post(route('dictaminador.convocatorias.store'), $data)
            ->assertSessionHasErrors('detalles.plazas_seleccionadas');

        $this->assertDatabaseCount('convocatorias', 0);
    }

    #[DataProvider('invalidTeachingPositionSelections')]
    public function test_docente_convocatoria_rejects_invalid_or_duplicate_selected_plazas(
        array $selectedPlazas,
        string $errorKey,
    ): void {
        $reviewer = User::factory()->create(['role' => User::ROLE_REVIEWER]);
        $data = $this->convocatoriaData();
        $data['detalles']['plazas_seleccionadas'] = $selectedPlazas;

        $this->actingAs($reviewer)
            ->post(route('dictaminador.convocatorias.store'), $data)
            ->assertSessionHasErrors($errorKey);

        $this->assertDatabaseCount('convocatorias', 0);
    }

    /**
     * @return array<string, array{list<string>, string}>
     */
    public static function invalidTeachingPositionSelections(): array
    {
        return [
            'unknown code' => [['E9999'], 'detalles.plazas_seleccionadas.0'],
            'duplicate code' => [['E3617', 'E3617'], 'detalles.plazas_seleccionadas.1'],
        ];
    }

    public function test_non_docente_convocatoria_rejects_docente_plazas(): void
    {
        $reviewer = User::factory()->create(['role' => User::ROLE_REVIEWER]);
        $data = $this->convocatoriaData();
        $data['tipo_personal'] = 'No docente';
        $data['detalles'] = [
            'grupo_puesto' => 'Técnico especializado',
            'categoria_nivel' => 'Nivel 5',
            'plazas_disponibles' => 1,
            'salario' => '15000.00',
            'jornada_horario' => 'Matutino',
            'adscripcion' => 'Departamento de Sistemas',
            'escolaridad_minima' => 'Licenciatura terminada.',
            'competencias' => 'Mantenimiento y redes.',
            'requisitos_ingreso' => 'Cumplir el reglamento institucional.',
            'plazas_seleccionadas' => ['E3617'],
        ];

        $this->actingAs($reviewer)
            ->post(route('dictaminador.convocatorias.store'), $data)
            ->assertSessionHasErrors('detalles');

        $this->assertDatabaseCount('convocatorias', 0);
    }

    public function test_closed_convocatorias_reject_insufficient_seniority(): void
    {
        $reviewer = User::factory()->create(['role' => User::ROLE_REVIEWER]);
        $closedCases = [
            [
                'Docente',
                [
                    'categoria_actual' => 'Profesor Asociado',
                    'categoria_aspirada' => 'Profesor Titular',
                    'plazas_seleccionadas' => ['E3617'],
                    'fecha_evaluacion' => today()->addDays(40)->toDateString(),
                    'antiguedad_minima_meses' => 11,
                    'constancias_merito' => 'Constancias de desempeño.',
                    'comision_dictaminadora' => 'Comisión académica institucional',
                ],
            ],
            [
                'No docente',
                [
                    'grupo_puesto' => 'Administrativo',
                    'categoria_vacante' => 'Nivel 4',
                    'fecha_aplicacion_factores' => today()->addDays(30)->toDateString(),
                    'antiguedad_minima_meses' => 5,
                    'factor_aptitud' => 'Evaluación de aptitud.',
                    'factor_conocimientos' => 'Examen de conocimientos.',
                    'factor_disciplina' => 'Historial disciplinario.',
                    'factor_puntualidad' => 'Registro de puntualidad.',
                ],
            ],
        ];

        foreach ($closedCases as [$tipoPersonal, $details]) {
            $this->actingAs($reviewer)
                ->post(route('dictaminador.convocatorias.store'), [
                    ...$this->convocatoriaData(),
                    'tipo_personal' => $tipoPersonal,
                    'tipo_concurso' => 'Cerrada',
                    'detalles' => $details,
                ])
                ->assertSessionHasErrors('detalles.antiguedad_minima_meses');
        }

        $this->assertDatabaseCount('convocatorias', 0);
    }

    public function test_docente_open_convocatoria_rejects_unlisted_evaluation_methods(): void
    {
        $reviewer = User::factory()->create(['role' => User::ROLE_REVIEWER]);
        $data = $this->convocatoriaData();
        $data['detalles']['pruebas_evaluacion'] = ['Evaluación no autorizada'];

        $this->actingAs($reviewer)
            ->post(route('dictaminador.convocatorias.store'), $data)
            ->assertSessionHasErrors('detalles.pruebas_evaluacion.0');

        $this->assertDatabaseCount('convocatorias', 0);
    }

    public function test_convocatoria_rejects_unsupported_contest_type(): void
    {
        $reviewer = User::factory()->create(['role' => User::ROLE_REVIEWER]);

        $this->actingAs($reviewer)
            ->post(route('dictaminador.convocatorias.store'), [
                ...$this->convocatoriaData(),
                'tipo_concurso' => 'Público Abierto',
            ])
            ->assertSessionHasErrors('tipo_concurso');

        $this->assertDatabaseCount('convocatorias', 0);
    }

    public function test_reviewer_dashboard_shows_live_statistics_and_expiring_convocatorias(): void
    {
        $reviewer = User::factory()->create(['role' => User::ROLE_REVIEWER]);
        $applicant = User::factory()->create();
        $expiring = $this->createConvocatoria([
            'titulo' => 'Convocatoria por vencer',
            'fecha_fin' => today()->addDays(3),
        ]);
        $completed = $this->createConvocatoria([
            'titulo' => 'Convocatoria concluida',
            'estatus' => 'Cerrada',
            'fecha_inicio' => today()->subDays(30),
            'fecha_fin' => today()->subDays(20),
        ]);
        Postulacion::create([
            'convocatoria_id' => $expiring->id,
            'user_id' => $applicant->id,
            'estatus' => Postulacion::STATUS_PENDING,
        ]);
        Postulacion::create([
            'convocatoria_id' => $completed->id,
            'user_id' => $applicant->id,
            'estatus' => Postulacion::STATUS_APPROVED,
        ]);

        $this->actingAs($reviewer)
            ->get(route('dictaminador.dashboard'))
            ->assertOk()
            ->assertSee('Convocatoria por vencer')
            ->assertSee('Convocatoria concluida')
            ->assertSee('Postulaciones pendientes')
            ->assertSee('Convocatorias a punto de vencer')
            ->assertSee($applicant->name)
            ->assertSee($applicant->email)
            ->assertDontSee('Matriz de Categorías')
            ->assertViewHas('convocatoriasCount', 2)
            ->assertViewHas('convocatoriasActivasCount', 1)
            ->assertViewHas('postulantesCount', 1)
            ->assertViewHas('pendingCount', 1)
            ->assertDontSee('Usuarios en el sistema');
    }

    public function test_admin_dashboard_shows_exact_live_system_counts(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $applicant = User::factory()->create();
        User::factory()->create(['role' => User::ROLE_REVIEWER]);
        $convocatoria = $this->createConvocatoria();
        Postulacion::create([
            'convocatoria_id' => $convocatoria->id,
            'user_id' => $applicant->id,
            'estatus' => Postulacion::STATUS_PENDING,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewHas('convocatoriasCount', 1)
            ->assertViewHas('usersCount', 3)
            ->assertViewHas('pendingCount', 1);
    }

    public function test_applicant_pdf_is_private_and_reviewer_decision_notifies_the_applicant(): void
    {
        Storage::fake('local');
        $applicant = User::factory()->create(['tipo_usuario' => 'aspirante_docente']);
        $reviewer = User::factory()->create(['role' => User::ROLE_REVIEWER]);
        $convocatoria = $this->createConvocatoria();
        $documentKeys = [
            'cv_documentado',
            'titulos_grados',
            'cedula_profesional',
            'identificacion_oficial',
        ];

        $this->actingAs($applicant)
            ->post(route('postulaciones.store', $convocatoria), [
                'informacion' => 'Experiencia docente de cinco años.',
                'documentos' => $this->uploadedDocuments($documentKeys),
            ])
            ->assertRedirect();

        $postulacion = Postulacion::query()->with('documentos')->firstOrFail();
        $this->assertDatabaseHas('postulaciones', [
            'id' => $postulacion->id,
            'estatus' => Postulacion::STATUS_PENDING,
            'user_id' => $applicant->id,
        ]);
        $this->assertCount(count($documentKeys), $postulacion->documentos);
        foreach ($postulacion->documentos as $documento) {
            Storage::disk('local')->assertExists($documento->path);
        }
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $reviewer->id,
            'type' => 'App\\Notifications\\NuevaPostulacion',
        ]);

        $this->actingAs($applicant)
            ->get(route('postulaciones.show', $postulacion))
            ->assertOk()
            ->assertSee('Experiencia docente de cinco años.')
            ->assertSee('Currículum Vitae documentado en PDF');

        $documento = $postulacion->documentos->firstOrFail();
        $this->actingAs($applicant)
            ->get(route('postulaciones.documento-requerido', [$postulacion, $documento]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
        $otherApplicant = User::factory()->create(['role' => User::ROLE_USER]);
        $this->actingAs($otherApplicant)
            ->get(route('postulaciones.show', $postulacion))
            ->assertNotFound();
        $this->actingAs($otherApplicant)
            ->get(route('postulaciones.documento-requerido', [$postulacion, $documento]))
            ->assertNotFound();
        $this->actingAs($applicant)
            ->get(route('dictaminador.postulantes.required-document', [$postulacion, $documento]))
            ->assertForbidden();

        $this->actingAs($reviewer)
            ->get(route('dashboard'))
            ->assertSee('Notificaciones, 1 nuevas');

        $this->actingAs($reviewer)
            ->get(route('dictaminador.postulantes.required-document', [$postulacion, $documento]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
        $this->actingAs($reviewer)
            ->get(route('dictaminador.postulantes.show', $postulacion))
            ->assertOk()
            ->assertSee('Documentación entregada')
            ->assertSee('Currículum Vitae documentado en PDF');

        $this->actingAs($reviewer)
            ->patch(route('dictaminador.postulantes.review', $postulacion), [
                'estatus' => Postulacion::STATUS_APPROVED,
            ])
            ->assertRedirect(route('dictaminador.postulantes.show', $postulacion));

        $this->assertSame(Postulacion::STATUS_APPROVED, $postulacion->refresh()->estatus);
        $this->assertSame($reviewer->id, $postulacion->revisado_por);
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $applicant->id,
            'type' => 'App\\Notifications\\PostulacionActualizada',
        ]);
        $this->assertDatabaseHas('audit_logs', [
            'event' => 'postulacion.reviewed',
            'actor_id' => $reviewer->id,
            'user_id' => $applicant->id,
        ]);

        $this->actingAs($applicant)
            ->get(route('dashboard'))
            ->assertSee('Notificaciones, 1 nuevas');

        $this->get(route('notifications.index'))
            ->assertOk()
            ->assertSee('Tu postulación a Convocatoria de prueba fue aprobada.');
    }

    #[DataProvider('applicantDocumentVariants')]
    public function test_applicant_can_submit_the_required_documents_for_each_convocatoria_type(
        string $tipoPersonal,
        string $tipoConcurso,
        array $documentKeys,
    ): void {
        Storage::fake('local');
        $applicant = User::factory()->create(['role' => User::ROLE_USER]);
        $convocatoria = $this->createConvocatoria([
            'tipo_personal' => $tipoPersonal,
            'tipo_concurso' => $tipoConcurso,
        ]);
        $requirements = $convocatoria->applicantDocumentRequirements();

        $formResponse = $this->actingAs($applicant)
            ->get(route('postulaciones.create', $convocatoria))
            ->assertOk()
            ->assertSee('Documentos solicitados');

        foreach ($documentKeys as $documentKey) {
            $formResponse->assertSee($requirements[$documentKey]['label']);
        }

        $this->actingAs($applicant)
            ->post(route('postulaciones.store', $convocatoria), [
                'documentos' => $this->uploadedDocuments($documentKeys),
            ])
            ->assertRedirect();

        $postulacion = Postulacion::query()->with('documentos')->firstOrFail();
        $this->assertCount(count($documentKeys), $postulacion->documentos);
        $this->assertDatabaseCount('postulacion_documentos', count($documentKeys));

        foreach ($documentKeys as $documentKey) {
            $documento = $postulacion->documentos->firstWhere('clave', $documentKey);
            $this->assertNotNull($documento);
            $this->assertSame($requirements[$documentKey]['label'], $documento->nombre);
            Storage::disk('local')->assertExists($documento->path);
        }

        $this->actingAs($applicant)
            ->get(route('postulaciones.index'))
            ->assertOk()
            ->assertSee('Convocatoria de prueba')
            ->assertSee('Ver expediente');

        $this->get(route('postulaciones.show', $postulacion))
            ->assertOk()
            ->assertSee('Documentación enviada');
    }

    public function test_applicant_cannot_submit_without_every_required_pdf(): void
    {
        Storage::fake('local');
        $applicant = User::factory()->create(['role' => User::ROLE_USER]);
        $convocatoria = $this->createConvocatoria();
        $documentKeys = [
            'cv_documentado',
            'titulos_grados',
            'cedula_profesional',
            'identificacion_oficial',
        ];
        $documents = $this->uploadedDocuments($documentKeys);
        unset($documents['identificacion_oficial']);

        $this->actingAs($applicant)
            ->post(route('postulaciones.store', $convocatoria), [
                'documentos' => $documents,
            ])
            ->assertSessionHasErrors('documentos.identificacion_oficial');

        $this->assertDatabaseCount('postulaciones', 0);
        $this->assertDatabaseCount('postulacion_documentos', 0);
        $this->assertSame([], Storage::disk('local')->allFiles());
    }

    public function test_applicant_cannot_submit_a_non_pdf_required_document(): void
    {
        Storage::fake('local');
        $applicant = User::factory()->create(['role' => User::ROLE_USER]);
        $convocatoria = $this->createConvocatoria();
        $documents = $this->uploadedDocuments([
            'cv_documentado',
            'titulos_grados',
            'cedula_profesional',
            'identificacion_oficial',
        ]);
        $documents['cv_documentado'] = UploadedFile::fake()->create('curriculum.txt', 50, 'text/plain');

        $this->actingAs($applicant)
            ->post(route('postulaciones.store', $convocatoria), ['documentos' => $documents])
            ->assertSessionHasErrors('documentos.cv_documentado');

        $this->assertDatabaseCount('postulaciones', 0);
        $this->assertSame([], Storage::disk('local')->allFiles());
    }

    public function test_convocatoria_with_postulations_cannot_be_deleted(): void
    {
        $reviewer = User::factory()->create(['role' => User::ROLE_REVIEWER]);
        $applicant = User::factory()->create();
        $convocatoria = $this->createConvocatoria();
        $postulacion = Postulacion::create([
            'convocatoria_id' => $convocatoria->id,
            'user_id' => $applicant->id,
        ]);

        $this->actingAs($reviewer)
            ->delete(route('dictaminador.convocatorias.destroy', $convocatoria))
            ->assertSessionHasErrors('convocatoria');

        $this->assertModelExists($convocatoria);
        $this->assertModelExists($postulacion);
    }

    public function test_user_cannot_apply_to_a_closed_convocatoria(): void
    {
        $applicant = User::factory()->create();
        $convocatoria = $this->createConvocatoria(['estatus' => 'Cerrada']);

        $this->actingAs($applicant)
            ->post(route('postulaciones.store', $convocatoria), [
                'informacion' => 'Información',
            ])
            ->assertSessionHasErrors('convocatoria');

        $this->assertDatabaseCount('postulaciones', 0);
        $this->assertDatabaseCount('notifications', 0);
    }

    public function test_duplicate_application_is_rejected_without_creating_a_second_record(): void
    {
        $applicant = User::factory()->create();
        $convocatoria = $this->createConvocatoria();
        Postulacion::create([
            'convocatoria_id' => $convocatoria->id,
            'user_id' => $applicant->id,
        ]);

        $this->actingAs($applicant)
            ->post(route('postulaciones.store', $convocatoria), [
                'informacion' => 'Información duplicada',
            ])
            ->assertSessionHasErrors('convocatoria');

        $this->assertDatabaseCount('postulaciones', 1);
    }

    public function test_applicant_cannot_review_or_access_another_applicants_postulation(): void
    {
        $owner = User::factory()->create();
        $otherApplicant = User::factory()->create();
        $reviewer = User::factory()->create(['role' => User::ROLE_REVIEWER]);
        $convocatoria = $this->createConvocatoria();
        $postulacion = Postulacion::create([
            'convocatoria_id' => $convocatoria->id,
            'user_id' => $owner->id,
        ]);

        $this->actingAs($otherApplicant)
            ->get(route('postulaciones.show', $postulacion))
            ->assertNotFound();

        $this->actingAs($owner)
            ->patch(route('dictaminador.postulantes.review', $postulacion), [
                'estatus' => Postulacion::STATUS_APPROVED,
            ])
            ->assertForbidden();

        $this->actingAs($reviewer)
            ->patch(route('dictaminador.postulantes.review', $postulacion), [
                'estatus' => Postulacion::STATUS_REJECTED,
            ])
            ->assertSessionHasErrors('comentarios_revision');

        $this->assertSame(Postulacion::STATUS_PENDING, $postulacion->refresh()->estatus);
    }

    public function test_only_reviewers_can_manage_convocatorias_and_review_applicants(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        foreach ([$user, $admin] as $account) {
            $this->actingAs($account);
            $this->get(route('dictaminador.convocatorias.index'))->assertForbidden();
            $this->get(route('dictaminador.convocatorias.create'))->assertForbidden();
            $this->post(route('dictaminador.convocatorias.store'), $this->convocatoriaData())->assertForbidden();
            $this->get(route('dictaminador.postulantes.index'))->assertForbidden();
        }
    }

    /**
     * @return array<string, array{string, string, list<string>}>
     */
    public static function applicantDocumentVariants(): array
    {
        return [
            'docente abierta' => [
                'Docente',
                'Abierta',
                ['cv_documentado', 'titulos_grados', 'cedula_profesional', 'identificacion_oficial'],
            ],
            'docente cerrada' => [
                'Docente',
                'Cerrada',
                ['antiguedad_definitiva', 'cv_actualizacion_merito', 'evaluaciones_desempeno'],
            ],
            'no docente abierta' => [
                'No docente',
                'Abierta',
                ['solicitud_cv', 'comprobante_estudios', 'identificacion_curp', 'certificado_medico'],
            ],
            'no docente cerrada' => [
                'No docente',
                'Cerrada',
                ['constancia_base_antiguedad', 'comprobantes_capacitacion_escolaridad', 'historial_laboral'],
            ],
        ];
    }

    /**
     * @param  list<string>  $keys
     * @return array<string, UploadedFile>
     */
    private function uploadedDocuments(array $keys): array
    {
        $documents = [];

        foreach ($keys as $key) {
            $documents[$key] = UploadedFile::fake()->create($key.'.pdf', 50, 'application/pdf');
        }

        return $documents;
    }

    /**
     * @return array<string, mixed>
     */
    private function convocatoriaData(): array
    {
        return [
            'titulo' => 'Convocatoria de prueba',
            'tipo_concurso' => 'Abierta',
            'tipo_personal' => 'Docente',
            'fecha_inicio' => today()->toDateString(),
            'fecha_fin' => today()->addMonth()->toDateString(),
            'estatus' => 'Activa',
            'requisitos_especificos' => 'Título profesional.',
            'detalles' => [
                'categoria_nivel' => 'Profesor Asociado',
                'plazas_seleccionadas' => ['E3617', 'E3817'],
                'plazas_disponibles' => 2,
                'jornada_horario' => 'Tiempo completo',
                'salario' => '18000.00',
                'area_materia' => 'Matemáticas',
                'perfil_academico' => 'Maestría en Matemáticas.',
                'pruebas_evaluacion' => ['Interrogatorio'],
                'documentacion_obligatoria' => 'Título y cédula profesional.',
            ],
        ];
    }

    /**
     * @return array<string, array{string, string, array<string, mixed>}>
     */
    public static function convocatoriaVariants(): array
    {
        return [
            'docente abierta' => [
                'Docente',
                'Abierta',
                [
                    'categoria_nivel' => 'Profesor Asociado',
                    'plazas_seleccionadas' => ['E3617', 'E3817'],
                    'plazas_disponibles' => 2,
                    'jornada_horario' => 'Tiempo completo',
                    'salario' => '18000.00',
                    'area_materia' => 'Matemáticas',
                    'perfil_academico' => 'Maestría en Matemáticas.',
                    'pruebas_evaluacion' => ['Interrogatorio'],
                    'documentacion_obligatoria' => 'Título y cédula profesional.',
                ],
            ],
            'docente cerrada' => [
                'Docente',
                'Cerrada',
                [
                    'categoria_actual' => 'Profesor Asociado',
                    'categoria_aspirada' => 'Profesor Titular',
                    'plazas_seleccionadas' => ['E3815'],
                    'fecha_evaluacion' => today()->addDays(40)->toDateString(),
                    'antiguedad_minima_meses' => 12,
                    'constancias_merito' => 'Constancias de desempeño.',
                    'comision_dictaminadora' => 'Comisión académica institucional',
                ],
            ],
            'no docente abierta' => [
                'No docente',
                'Abierta',
                [
                    'grupo_puesto' => 'Técnico especializado',
                    'categoria_nivel' => 'Nivel 5',
                    'plazas_disponibles' => 1,
                    'salario' => '15000.00',
                    'jornada_horario' => 'Matutino',
                    'adscripcion' => 'Departamento de Sistemas',
                    'escolaridad_minima' => 'Licenciatura terminada.',
                    'competencias' => 'Mantenimiento y redes.',
                    'requisitos_ingreso' => 'Cumplir el reglamento institucional.',
                ],
            ],
            'no docente cerrada' => [
                'No docente',
                'Cerrada',
                [
                    'grupo_puesto' => 'Administrativo',
                    'categoria_vacante' => 'Nivel 4',
                    'fecha_aplicacion_factores' => today()->addDays(30)->toDateString(),
                    'antiguedad_minima_meses' => 6,
                    'factor_aptitud' => 'Evaluación de aptitud.',
                    'factor_conocimientos' => 'Examen de conocimientos.',
                    'factor_disciplina' => 'Historial disciplinario.',
                    'factor_puntualidad' => 'Registro de puntualidad.',
                ],
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function createConvocatoria(array $overrides = []): Convocatoria
    {
        return Convocatoria::create([
            'titulo' => 'Convocatoria de prueba',
            'tipo_concurso' => 'Abierta',
            'tipo_personal' => 'Docente',
            'categoria_nivel' => 'Profesor Asociado',
            'plaza_disponible' => '2 plazas',
            'jornada_horario' => 'Tiempo completo',
            'salario' => '18000.00',
            'fecha_inicio' => today()->toDateString(),
            'fecha_fin' => today()->addMonth()->toDateString(),
            'estatus' => 'Activa',
            'requisitos_especificos' => 'Título profesional.',
            'detalles_especificos' => [],
            ...$overrides,
        ]);
    }
}
