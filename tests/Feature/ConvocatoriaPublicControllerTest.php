<?php

namespace Tests\Feature;

use App\Models\Convocatoria;
use App\Models\Postulacion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ConvocatoriaPublicControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_filters_active_convocatorias_by_contest_and_personnel_type(): void
    {
        $matching = $this->createConvocatoria([
            'titulo' => 'Abierta no docente',
            'tipo_concurso' => 'Abierta',
            'tipo_personal' => 'No docente',
        ]);
        $this->createConvocatoria([
            'titulo' => 'Cerrada no docente',
            'tipo_concurso' => 'Cerrada',
            'tipo_personal' => 'No docente',
        ]);
        $this->createConvocatoria([
            'titulo' => 'Abierta docente',
            'tipo_concurso' => 'Abierta',
            'tipo_personal' => 'Docente',
        ]);
        $this->createConvocatoria([
            'titulo' => 'Borrador no docente',
            'tipo_concurso' => 'Abierta',
            'tipo_personal' => 'No docente',
            'estatus' => 'Borrador',
        ]);

        $this->get(route('home', [
            'tipo_concurso' => 'Abierta',
            'tipo_personal' => 'No docente',
        ]))
            ->assertOk()
            ->assertSee($matching->titulo)
            ->assertDontSee('Cerrada no docente')
            ->assertDontSee('Abierta docente')
            ->assertDontSee('Borrador no docente');
    }

    public static function applicantProfiles(): array
    {
        return [
            'docente aspirant' => ['aspirante_docente', 'Docente'],
            'non-teaching aspirant' => ['aspirante_no_docente', 'No docente'],
        ];
    }

    #[DataProvider('applicantProfiles')]
    public function test_home_prioritizes_convocatorias_matching_the_applicant_profile_without_hiding_others(
        string $profile,
        string $preferredPersonnelType,
    ): void {
        $this->createConvocatoria([
            'titulo' => 'Convocatoria no prioritaria',
            'tipo_personal' => $preferredPersonnelType === 'Docente' ? 'No docente' : 'Docente',
        ]);
        $this->createConvocatoria([
            'titulo' => 'Convocatoria prioritaria',
            'tipo_personal' => $preferredPersonnelType,
        ]);

        $this->actingAs(User::factory()->create(['tipo_usuario' => $profile]))
            ->get(route('home'))
            ->assertOk()
            ->assertSeeInOrder([
                'Convocatoria prioritaria',
                'Convocatoria no prioritaria',
            ]);
    }

    public function test_home_rejects_unsupported_convocatoria_filters(): void
    {
        $this->from(route('home'))
            ->get(route('home', ['tipo_personal' => 'Docente desc']))
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors('tipo_personal');
    }

    public function test_home_rejects_unsupported_contest_category_filter(): void
    {
        $this->from(route('home'))
            ->get(route('home', ['tipo_concurso' => 'Abierta OR 1=1']))
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors('tipo_concurso');
    }

    public function test_home_filters_and_highlights_convocatorias_related_to_an_upcoming_plaza(): void
    {
        $applicant = User::factory()->create([
            'role' => User::ROLE_USER,
            'tipo_usuario' => 'aspirante_docente',
            'plaza_jornada' => 'E3817',
        ]);
        $relatedConvocatoria = $this->createConvocatoria([
            'titulo' => 'Convocatoria relacionada con plaza próxima',
        ]);
        $unrelatedConvocatoria = $this->createConvocatoria([
            'titulo' => 'Convocatoria sin relación con plaza próxima',
        ]);

        Postulacion::query()->create([
            'convocatoria_id' => $relatedConvocatoria->id,
            'user_id' => $applicant->id,
            'estatus' => Postulacion::STATUS_PENDING,
        ]);
        Postulacion::query()->create([
            'convocatoria_id' => $unrelatedConvocatoria->id,
            'user_id' => $applicant->id,
            'estatus' => Postulacion::STATUS_REJECTED,
        ]);

        $this->get(route('home', ['plaza' => 'E3817']))
            ->assertOk()
            ->assertSee($relatedConvocatoria->titulo)
            ->assertDontSee($unrelatedConvocatoria->titulo)
            ->assertSee('id="convocatoria-'.$relatedConvocatoria->id.'"', false)
            ->assertSee('animate-convocatoria-highlight', false)
            ->assertViewHas('highlightedPlaza', 'E3817');
    }

    public function test_available_plaza_links_to_and_highlights_its_active_convocatoria(): void
    {
        $matchingConvocatoria = $this->createConvocatoria([
            'titulo' => 'Convocatoria con plaza seleccionada',
            'detalles_especificos' => ['plazas_seleccionadas' => ['E3617']],
        ]);
        $otherConvocatoria = $this->createConvocatoria([
            'titulo' => 'Convocatoria con otra plaza',
            'detalles_especificos' => ['plazas_seleccionadas' => ['E3817']],
        ]);

        $this->get(route('home', ['plaza' => 'E3617']))
            ->assertOk()
            ->assertSee($matchingConvocatoria->titulo)
            ->assertDontSee($otherConvocatoria->titulo)
            ->assertSee('id="convocatoria-'.$matchingConvocatoria->id.'"', false)
            ->assertSee('animate-convocatoria-highlight', false);

        $this->actingAs(User::factory()->create([
            'role' => User::ROLE_USER,
            'tipo_usuario' => 'aspirante_docente',
            'plaza_jornada' => 'E3807',
        ]))
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee(route('home', ['plaza' => 'E3617']).'#convocatorias', false);
    }

    public function test_home_rejects_unsupported_plaza_filter(): void
    {
        $this->from(route('home'))
            ->get(route('home', ['plaza' => 'E3817 OR 1=1']))
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors('plaza');
    }

    public function test_applicant_sees_a_postulate_button_instead_of_inline_upload_fields(): void
    {
        $applicant = User::factory()->create(['role' => User::ROLE_USER]);
        $convocatoria = $this->createConvocatoria();

        $this->actingAs($applicant)
            ->get(route('convocatorias.show', $convocatoria))
            ->assertOk()
            ->assertSee('Postularme')
            ->assertSee(route('postulaciones.create', $convocatoria))
            ->assertDontSee('name="documento"', false)
            ->assertDontSee('name="documentos[', false);
    }

    public function test_applicant_sees_submitted_convocatorias_as_applied_and_can_open_the_expedient(): void
    {
        $applicant = User::factory()->create(['role' => User::ROLE_USER]);
        $submittedConvocatoria = $this->createConvocatoria([
            'titulo' => 'Convocatoria ya postulada',
        ]);
        $availableConvocatoria = $this->createConvocatoria([
            'titulo' => 'Convocatoria disponible',
        ]);
        $postulacion = Postulacion::query()->create([
            'convocatoria_id' => $submittedConvocatoria->id,
            'user_id' => $applicant->id,
            'estatus' => Postulacion::STATUS_PENDING,
        ]);

        $this->actingAs($applicant)
            ->get(route('home'))
            ->assertOk()
            ->assertSee($submittedConvocatoria->titulo)
            ->assertSee($availableConvocatoria->titulo)
            ->assertSee('border-emerald-300', false)
            ->assertSee('bg-emerald-50/70', false)
            ->assertSee('Ver postulación')
            ->assertSee(route('postulaciones.show', $postulacion), false)
            ->assertSee('Ver convocatoria')
            ->assertSee(route('convocatorias.show', $availableConvocatoria), false);

        $this->get(route('convocatorias.show', $submittedConvocatoria))
            ->assertOk()
            ->assertSee('Ver expediente enviado')
            ->assertSee(route('postulaciones.show', $postulacion), false);
    }

    #[DataProvider('publicPresentationVariants')]
    public function test_home_and_detail_show_all_variant_information_and_required_documents(
        string $tipoPersonal,
        string $tipoConcurso,
        array $details,
        array $visibleText,
        array $visibleDocuments,
    ): void {
        $convocatoria = $this->createConvocatoria([
            'titulo' => 'Convocatoria con requisitos completos',
            'tipo_personal' => $tipoPersonal,
            'tipo_concurso' => $tipoConcurso,
            'requisitos_especificos' => 'Información adicional publicada.',
            'detalles_especificos' => $details,
        ]);

        foreach ([
            $this->get(route('home')),
            $this->get(route('convocatorias.show', $convocatoria)),
        ] as $response) {
            $response->assertOk()
                ->assertSee($convocatoria->titulo)
                ->assertSee('Información adicional publicada.');

            foreach ($visibleText as $text) {
                $response->assertSee($text);
            }

            foreach ($visibleDocuments as $document) {
                $response->assertSee($document);
            }
        }
    }

    /**
     * @return array<string, array{string, string, array<string, mixed>, list<string>, list<string>}>
     */
    public static function publicPresentationVariants(): array
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
                    'area_materia' => 'Álgebra',
                    'perfil_academico' => 'Doctorado en Matemáticas.',
                    'pruebas_evaluacion' => ['Interrogatorio'],
                    'documentacion_obligatoria' => 'Acreditación de experiencia.',
                ],
                ['Plazas seleccionadas', 'E3617', 'Nivel 17', '½ Tiempo', 'E3817', 'Tiempo Completo', 'Área o materia', 'Álgebra', 'Doctorado en Matemáticas.', 'Interrogatorio', 'Acreditación de experiencia.'],
                ['Currículum Vitae documentado en PDF', 'Debe incluir constancias de experiencia docente y profesional.', 'Cédula profesional en PDF'],
            ],
            'docente cerrada' => [
                'Docente',
                'Cerrada',
                [
                    'categoria_actual' => 'Profesor Asociado',
                    'categoria_aspirada' => 'Profesor Titular',
                    'plazas_seleccionadas' => ['E3815'],
                    'fecha_evaluacion' => '2026-12-10',
                    'antiguedad_minima_meses' => 12,
                    'constancias_merito' => 'Constancias de publicaciones recientes.',
                    'comision_dictaminadora' => 'Comisión académica institucional',
                ],
                ['Plazas seleccionadas', 'E3815', 'Nivel 15', 'Tiempo Completo', 'Categoría actual', 'Profesor Titular', 'Fecha de evaluación', '12 meses', 'Constancias de publicaciones recientes.'],
                ['Constancia de antigüedad definitiva en PDF', 'al menos un año de servicio ininterrumpido', 'Evaluaciones del desempeño docente en PDF'],
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
                ['Grupo del puesto', 'Departamento de Sistemas', 'Escolaridad mínima', 'Mantenimiento y redes.', 'Cumplir el reglamento institucional.'],
                ['Solicitud de empleo o Curriculum Vitae en PDF', 'Adjunte ambos documentos en un solo archivo PDF.', 'Certificado médico general en PDF'],
            ],
            'no docente cerrada' => [
                'No docente',
                'Cerrada',
                [
                    'grupo_puesto' => 'Administrativo',
                    'categoria_vacante' => 'Nivel 4',
                    'fecha_aplicacion_factores' => '2026-12-10',
                    'antiguedad_minima_meses' => 6,
                    'factor_aptitud' => 'Evaluación de aptitud.',
                    'factor_conocimientos' => 'Cursos internos acreditados.',
                    'factor_disciplina' => 'Historial disciplinario.',
                    'factor_puntualidad' => 'Registro de puntualidad.',
                ],
                ['Grupo del puesto', 'Categoría de la vacante', '6 meses', 'Cursos internos acreditados.', 'Historial disciplinario.', 'Registro de puntualidad.'],
                ['Constancia de base y antigüedad en PDF', 'mínimo de seis meses de base', 'Constancia de historial laboral o expediente en PDF'],
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
            'fecha_inicio' => today(),
            'fecha_fin' => today()->addMonth(),
            'estatus' => 'Activa',
            'requisitos_especificos' => 'Título profesional.',
            ...$overrides,
        ]);
    }
}
