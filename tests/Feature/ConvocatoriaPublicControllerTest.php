<?php

namespace Tests\Feature;

use App\Models\Convocatoria;
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
