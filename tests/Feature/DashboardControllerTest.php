<?php

namespace Tests\Feature;

use App\Models\Convocatoria;
use App\Models\Postulacion;
use App\Models\User;
use App\Notifications\PostulacionActualizada;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_applicant_dashboard_shows_their_postulation_and_expedient_link(): void
    {
        $applicant = User::factory()->create(['role' => User::ROLE_USER]);
        $convocatoria = $this->createConvocatoria('No docente');
        $postulacion = Postulacion::query()->create([
            'convocatoria_id' => $convocatoria->id,
            'user_id' => $applicant->id,
            'estatus' => Postulacion::STATUS_PENDING,
        ]);
        $applicant->notify(new PostulacionActualizada($postulacion, 'Tu expediente está en revisión.'));

        $this->actingAs($applicant)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Mis postulaciones')
            ->assertSeeInOrder([
                'Mis postulaciones',
                '1',
                'Expedientes enviados',
                'Notificaciones nuevas',
                '1',
                'Consultar notificaciones',
            ])
            ->assertSee($convocatoria->titulo)
            ->assertSee('Pendiente')
            ->assertSee(route('postulaciones.show', $postulacion))
            ->assertViewHas('unreadNotificationsCount', 1)
            ->assertViewHas(
                'postulaciones',
                fn (LengthAwarePaginator $postulaciones): bool => $postulaciones->total() === 1,
            );
    }

    public function test_applicant_dashboard_prioritizes_own_occupied_and_available_plazas(): void
    {
        $applicant = User::factory()->create([
            'role' => User::ROLE_USER,
            'tipo_usuario' => 'aspirante_docente',
            'plaza_jornada' => 'E3817',
        ]);

        User::factory()->create([
            'role' => User::ROLE_USER,
            'tipo_usuario' => 'aspirante_docente',
            'plaza_jornada' => 'E3817',
        ]);
        User::factory()->create([
            'role' => User::ROLE_USER,
            'tipo_usuario' => 'aspirante_docente',
            'plaza_jornada' => 'E3815',
        ]);

        $this->createConvocatoria('Docente', ['E3617', 'E3815']);

        $response = $this->actingAs($applicant)->get(route('dashboard'));

        $response->assertOk()
            ->assertSee('data-plaza-code="E3817"', false)
            ->assertSee('data-plaza-status="own"', false)
            ->assertSee('aria-label="Plaza E3817: Tu plaza"', false)
            ->assertSee('data-plaza-code="E3815"', false)
            ->assertSee('data-plaza-status="occupied"', false)
            ->assertSee('aria-label="Plaza E3815: Ocupada"', false)
            ->assertSee('data-plaza-code="E3617"', false)
            ->assertSee('data-plaza-status="available"', false)
            ->assertSee('aria-label="Plaza E3617: Disponible en convocatoria"', false)
            ->assertSee('data-plaza-code="E3607"', false)
            ->assertSee('data-plaza-status="none"', false)
            ->assertSee('bg-violet-100', false)
            ->assertSee('bg-rose-100', false)
            ->assertSee('bg-emerald-100', false)
            ->assertSee('Disponible en convocatoria');
    }

    public function test_teacher_postulation_marks_the_owners_plaza_as_upcoming_until_rejected(): void
    {
        Storage::fake('local');

        $applicant = User::factory()->create([
            'role' => User::ROLE_USER,
            'tipo_usuario' => 'aspirante_docente',
            'plaza_jornada' => 'E3817',
        ]);
        $otherApplicant = User::factory()->create([
            'role' => User::ROLE_USER,
            'tipo_usuario' => 'aspirante_docente',
            'plaza_jornada' => 'E3807',
        ]);
        $noDocenteApplicant = User::factory()->create([
            'role' => User::ROLE_USER,
            'tipo_usuario' => 'aspirante_docente',
            'plaza_jornada' => 'E3815',
        ]);

        $teachingConvocatoria = $this->createConvocatoria('Docente', ['E3617']);
        $noDocenteConvocatoria = $this->createConvocatoria('No docente');

        $documents = [];

        foreach (array_keys($teachingConvocatoria->applicantDocumentRequirements()) as $key) {
            $documents[$key] = UploadedFile::fake()->create($key.'.pdf', 50, 'application/pdf');
        }

        $this->actingAs($applicant)
            ->post(route('postulaciones.store', $teachingConvocatoria), [
                'documentos' => $documents,
            ])
            ->assertRedirect(route('postulaciones.show', Postulacion::query()
                ->where('convocatoria_id', $teachingConvocatoria->id)
                ->where('user_id', $applicant->id)
                ->firstOrFail()));

        $postulacion = Postulacion::query()
            ->where('convocatoria_id', $teachingConvocatoria->id)
            ->where('user_id', $applicant->id)
            ->firstOrFail();

        $this->assertSame(Postulacion::STATUS_PENDING, $postulacion->estatus);

        Postulacion::query()->create([
            'convocatoria_id' => $noDocenteConvocatoria->id,
            'user_id' => $noDocenteApplicant->id,
            'estatus' => Postulacion::STATUS_PENDING,
        ]);

        foreach ([$applicant, $otherApplicant] as $dashboardUser) {
            $this->actingAs($dashboardUser)
                ->get(route('dashboard'))
                ->assertOk()
                ->assertSee('data-plaza-code="E3817"', false)
                ->assertSee('data-plaza-status="upcoming"', false)
                ->assertSee('aria-label="Plaza E3817: Próxima disponibilidad"', false)
                ->assertSee('data-plaza-code="E3815"', false)
                ->assertSee('data-plaza-status="occupied"', false)
                ->assertSee('data-plaza-code="E3617"', false)
                ->assertSee('data-plaza-status="available"', false);
        }

        $postulacion->update(['estatus' => Postulacion::STATUS_APPROVED]);

        $this->actingAs($otherApplicant)
            ->get(route('dashboard'))
            ->assertSee('data-plaza-status="upcoming"', false);

        $postulacion->update(['estatus' => Postulacion::STATUS_REJECTED]);

        $this->actingAs($otherApplicant)
            ->get(route('dashboard'))
            ->assertSee('aria-label="Plaza E3817: Ocupada"', false);
    }

    /**
     * @param  array<int, string>  $plazaCodes
     */
    private function createConvocatoria(string $tipoPersonal, array $plazaCodes = []): Convocatoria
    {
        return Convocatoria::query()->create([
            'titulo' => 'Convocatoria de prueba',
            'tipo_concurso' => 'Abierta',
            'tipo_personal' => $tipoPersonal,
            'categoria_nivel' => 'Profesor Asociado',
            'plaza_disponible' => '2 plazas',
            'jornada_horario' => 'Tiempo completo',
            'salario' => '18000.00',
            'fecha_inicio' => today()->toDateString(),
            'fecha_fin' => today()->addMonth()->toDateString(),
            'estatus' => 'Activa',
            'requisitos_especificos' => 'Requisitos de prueba.',
            'detalles_especificos' => [
                'plazas_seleccionadas' => $plazaCodes,
            ],
        ]);
    }
}
