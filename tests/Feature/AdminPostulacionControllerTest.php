<?php

namespace Tests\Feature;

use App\Models\Convocatoria;
use App\Models\Postulacion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPostulacionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_user_expedients_and_return_pending_application_for_editing(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $reviewer = User::factory()->create(['role' => User::ROLE_REVIEWER]);
        $applicant = User::factory()->create(['role' => User::ROLE_USER]);
        $convocatoria = $this->createConvocatoria();
        $postulacion = $this->createPostulacion($applicant, $convocatoria, Postulacion::STATUS_PENDING);
        $documentPaths = $this->createDocuments($postulacion, $convocatoria);
        $documento = $postulacion->documentos()->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.postulaciones.index'))
            ->assertOk()
            ->assertSee('<details', false)
            ->assertSee($applicant->name)
            ->assertSee($convocatoria->titulo)
            ->assertSee('Devolver documentos para edición')
            ->assertSee(route('admin.postulaciones.documento-requerido', [$postulacion, $documento]), false);

        $this->get(route('admin.postulaciones.documento-requerido', [$postulacion, $documento]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->post(route('admin.postulaciones.return-for-editing', $postulacion))
            ->assertRedirect(route('admin.postulaciones.index'))
            ->assertSessionHas('status');

        $postulacion->refresh();
        $this->assertSame(Postulacion::STATUS_RETURNED, $postulacion->estatus);
        $this->assertSame($admin->id, $postulacion->revisado_por);
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $applicant->id,
            'type' => 'App\\Notifications\\PostulacionActualizada',
        ]);
        $this->assertDatabaseHas('audit_logs', [
            'event' => 'postulacion.returned_for_editing',
            'actor_id' => $admin->id,
            'user_id' => $applicant->id,
        ]);

        foreach ($documentPaths as $path) {
            Storage::disk('local')->assertExists($path);
        }

        $this->actingAs($applicant)
            ->get(route('postulaciones.show', $postulacion))
            ->assertOk()
            ->assertSee('Editar expediente')
            ->assertSee(route('postulaciones.edit', $postulacion), false);

        $this->actingAs($reviewer)
            ->get(route('dictaminador.postulantes.show', $postulacion))
            ->assertOk()
            ->assertSee('devuelto al usuario')
            ->assertDontSee('name="estatus" value="aprobada"', false)
            ->assertDontSee('name="estatus" value="rechazada"', false);
    }

    public function test_only_admin_can_list_and_return_applications(): void
    {
        $applicant = User::factory()->create(['role' => User::ROLE_USER]);
        $convocatoria = $this->createConvocatoria();
        $postulacion = $this->createPostulacion($applicant, $convocatoria, Postulacion::STATUS_PENDING);

        $this->get(route('admin.postulaciones.index'))->assertRedirect(route('login'));

        foreach ([User::ROLE_USER, User::ROLE_REVIEWER] as $role) {
            $this->actingAs(User::factory()->create(['role' => $role]));
            $this->get(route('admin.postulaciones.index'))->assertForbidden();
            $this->post(route('admin.postulaciones.return-for-editing', $postulacion))->assertForbidden();
        }
    }

    public function test_admin_cannot_return_an_already_decided_application(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $applicant = User::factory()->create(['role' => User::ROLE_USER]);
        $convocatoria = $this->createConvocatoria();
        $postulacion = $this->createPostulacion($applicant, $convocatoria, Postulacion::STATUS_APPROVED);

        $this->actingAs($admin)
            ->from(route('admin.postulaciones.index'))
            ->post(route('admin.postulaciones.return-for-editing', $postulacion))
            ->assertRedirect(route('admin.postulaciones.index'))
            ->assertSessionHasErrors('postulacion');

        $this->assertSame(Postulacion::STATUS_APPROVED, $postulacion->refresh()->estatus);
        $this->assertDatabaseMissing('audit_logs', ['event' => 'postulacion.returned_for_editing']);
    }

    public function test_applicant_cannot_edit_an_expedient_until_an_admin_returns_it(): void
    {
        Storage::fake('local');
        $applicant = User::factory()->create(['role' => User::ROLE_USER]);
        $convocatoria = $this->createConvocatoria();
        $postulacion = $this->createPostulacion($applicant, $convocatoria, Postulacion::STATUS_PENDING);
        $originalText = data_get($postulacion->informacion, 'texto');

        $this->actingAs($applicant)
            ->get(route('postulaciones.edit', $postulacion))
            ->assertForbidden();

        $this->patch(route('postulaciones.update', $postulacion), [
            'informacion' => 'Cambio no autorizado',
        ])->assertForbidden();

        $this->assertSame(Postulacion::STATUS_PENDING, $postulacion->refresh()->estatus);
        $this->assertSame($originalText, data_get($postulacion->informacion, 'texto'));
    }

    public function test_applicant_cannot_edit_another_users_returned_expedient(): void
    {
        $owner = User::factory()->create(['role' => User::ROLE_USER]);
        $otherApplicant = User::factory()->create(['role' => User::ROLE_USER]);
        $convocatoria = $this->createConvocatoria();
        $postulacion = $this->createPostulacion($owner, $convocatoria, Postulacion::STATUS_RETURNED);

        $this->actingAs($otherApplicant)
            ->get(route('postulaciones.edit', $postulacion))
            ->assertNotFound();

        $this->patch(route('postulaciones.update', $postulacion), [
            'informacion' => 'Cambio ajeno',
        ])->assertNotFound();

        $this->assertSame('Información original', data_get($postulacion->refresh()->informacion, 'texto'));
    }

    public function test_applicant_can_replace_documents_and_resubmit_a_returned_expedient(): void
    {
        Storage::fake('local');
        $applicant = User::factory()->create(['role' => User::ROLE_USER]);
        $reviewer = User::factory()->create(['role' => User::ROLE_REVIEWER]);
        $convocatoria = $this->createConvocatoria();
        $postulacion = $this->createPostulacion($applicant, $convocatoria, Postulacion::STATUS_RETURNED);
        $oldDocumentPaths = $this->createDocuments($postulacion, $convocatoria);
        $replacement = UploadedFile::fake()->createWithContent(
            'curriculum-corregido.pdf',
            "%PDF-1.4\nContenido de prueba\n%%EOF",
        );

        $this->actingAs($applicant)
            ->get(route('postulaciones.edit', $postulacion))
            ->assertOk()
            ->assertSee('Corregir expediente')
            ->assertSee('Ver PDF actual');

        $this->patch(route('postulaciones.update', $postulacion), [
            'informacion' => 'Información corregida.',
            'documentos' => [
                'cv_documentado' => $replacement,
            ],
        ])
            ->assertRedirect(route('postulaciones.show', $postulacion))
            ->assertSessionHas('status');

        $postulacion->refresh();
        $this->assertSame(Postulacion::STATUS_PENDING, $postulacion->estatus);
        $this->assertSame('Información corregida.', data_get($postulacion->informacion, 'texto'));
        $this->assertSame($applicant->email, data_get($postulacion->informacion, 'perfil.email'));
        $this->assertNull($postulacion->revisado_por);
        $this->assertNull($postulacion->revisado_en);
        $this->assertDatabaseHas('audit_logs', [
            'event' => 'postulacion.resubmitted',
            'actor_id' => $applicant->id,
            'user_id' => $applicant->id,
        ]);
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $reviewer->id,
            'type' => 'App\\Notifications\\NuevaPostulacion',
        ]);

        $replacedDocument = $postulacion->documentos()->where('clave', 'cv_documentado')->firstOrFail();
        Storage::disk('local')->assertMissing($oldDocumentPaths['cv_documentado']);
        $this->assertSame('local', $replacedDocument->storage_disk);
        Storage::disk('local')->assertExists($replacedDocument->path);

        foreach ($oldDocumentPaths as $key => $path) {
            if ($key !== 'cv_documentado') {
                Storage::disk('local')->assertExists($path);
            }
        }

        $this->actingAs($reviewer)
            ->get(route('dictaminador.postulantes.show', $postulacion))
            ->assertOk()
            ->assertSee('Aprobar')
            ->assertSee('Rechazar');

        $this->actingAs($applicant)
            ->get(route('postulaciones.show', $postulacion))
            ->assertOk()
            ->assertDontSee('Editar expediente')
            ->assertSee('Información corregida.');
    }

    public function test_applicant_must_change_information_or_documents_before_resubmitting(): void
    {
        Storage::fake('local');
        $applicant = User::factory()->create(['role' => User::ROLE_USER]);
        $convocatoria = $this->createConvocatoria();
        $postulacion = $this->createPostulacion($applicant, $convocatoria, Postulacion::STATUS_RETURNED);
        $documentPaths = $this->createDocuments($postulacion, $convocatoria);

        $this->actingAs($applicant)
            ->from(route('postulaciones.edit', $postulacion))
            ->patch(route('postulaciones.update', $postulacion), [
                'informacion' => 'Información original',
            ])
            ->assertRedirect(route('postulaciones.edit', $postulacion))
            ->assertSessionHasErrors('expediente');

        $this->assertSame(Postulacion::STATUS_RETURNED, $postulacion->refresh()->estatus);

        foreach ($documentPaths as $path) {
            Storage::disk('local')->assertExists($path);
        }
    }

    public function test_applicant_cannot_replace_a_document_with_a_non_pdf_file(): void
    {
        Storage::fake('local');
        $applicant = User::factory()->create(['role' => User::ROLE_USER]);
        $convocatoria = $this->createConvocatoria();
        $postulacion = $this->createPostulacion($applicant, $convocatoria, Postulacion::STATUS_RETURNED);
        $documentPaths = $this->createDocuments($postulacion, $convocatoria);

        $this->actingAs($applicant)
            ->patch(route('postulaciones.update', $postulacion), [
                'informacion' => 'Información original',
                'documentos' => [
                    'cv_documentado' => UploadedFile::fake()->create('curriculum.txt', 50, 'text/plain'),
                ],
            ])
            ->assertSessionHasErrors('documentos.cv_documentado');

        $this->assertSame(Postulacion::STATUS_RETURNED, $postulacion->refresh()->estatus);
        Storage::disk('local')->assertExists($documentPaths['cv_documentado']);
    }

    private function createConvocatoria(): Convocatoria
    {
        return Convocatoria::query()->create([
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
            'requisitos_especificos' => 'Requisitos de prueba.',
        ]);
    }

    private function createPostulacion(User $applicant, Convocatoria $convocatoria, string $status): Postulacion
    {
        return Postulacion::query()->create([
            'convocatoria_id' => $convocatoria->id,
            'user_id' => $applicant->id,
            'estatus' => $status,
            'informacion' => [
                'texto' => 'Información original',
                'perfil' => ['email' => $applicant->email],
            ],
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function createDocuments(Postulacion $postulacion, Convocatoria $convocatoria): array
    {
        $paths = [];

        foreach ($convocatoria->applicantDocumentRequirements() as $key => $requirement) {
            $path = UploadedFile::fake()
                ->create($key.'.pdf', 50, 'application/pdf')
                ->store('postulaciones/'.$postulacion->id, 'local');

            $paths[$key] = $path;

            $postulacion->documentos()->create([
                'clave' => $key,
                'nombre' => $requirement['label'],
                'path' => $path,
            ]);
        }

        return $paths;
    }
}
