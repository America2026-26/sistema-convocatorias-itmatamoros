<?php

namespace Tests\Feature;

use App\Models\Convocatoria;
use App\Models\Postulacion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\FakesOneDrive;
use Tests\TestCase;

class OneDriveDocumentStorageTest extends TestCase
{
    use FakesOneDrive;
    use RefreshDatabase;

    public function test_command_migrates_existing_application_pdfs_to_onedrive_and_removes_local_copies(): void
    {
        Storage::fake('local');
        $this->fakeOneDrive();
        $postulacion = $this->createPostulacion();
        $documentPath = UploadedFile::fake()
            ->createWithContent('requisito.pdf', "%PDF-1.4\nRequisito de prueba\n%%EOF")
            ->store('postulaciones/'.$postulacion->id, 'local');
        $legacyPath = UploadedFile::fake()
            ->createWithContent('expediente.pdf', "%PDF-1.4\nExpediente de prueba\n%%EOF")
            ->store('postulaciones/'.$postulacion->id, 'local');
        $documento = $postulacion->documentos()->create([
            'clave' => 'requisito',
            'nombre' => 'Requisito en PDF',
            'path' => $documentPath,
        ]);
        $postulacion->update(['documento_path' => $legacyPath]);

        $this->artisan('postulaciones:migrate-local-documents-to-onedrive')
            ->assertSuccessful();

        $this->assertSame('onedrive', $documento->refresh()->storage_disk);
        $this->assertSame('onedrive-item-1', $documento->path);
        $this->assertSame('onedrive', $postulacion->refresh()->documento_storage_disk);
        $this->assertSame('onedrive-item-2', $postulacion->documento_path);
        Storage::disk('local')->assertMissing($documentPath);
        Storage::disk('local')->assertMissing($legacyPath);

        $this->actingAs($postulacion->user)
            ->get(route('postulaciones.documento-requerido', [$postulacion, $documento]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertHeader('cache-control', 'no-store, private')
            ->assertContent('%PDF-1.4 test document');
    }

    public function test_command_reports_missing_local_pdfs_without_changing_their_database_paths(): void
    {
        Storage::fake('local');
        $this->fakeOneDrive();
        $postulacion = $this->createPostulacion();
        $documento = $postulacion->documentos()->create([
            'clave' => 'requisito',
            'nombre' => 'Requisito en PDF',
            'path' => 'postulaciones/'.$postulacion->id.'/archivo-inexistente.pdf',
        ]);

        $this->artisan('postulaciones:migrate-local-documents-to-onedrive')
            ->assertFailed();

        $this->assertSame('local', $documento->refresh()->storage_disk);
        $this->assertSame('postulaciones/'.$postulacion->id.'/archivo-inexistente.pdf', $documento->path);
    }

    public function test_applicant_can_submit_documents_locally_without_onedrive_credentials(): void
    {
        Storage::fake('local');
        Http::preventStrayRequests();

        $applicant = User::factory()->create(['role' => User::ROLE_USER]);
        $convocatoria = $this->createConvocatoria();
        $documents = [];

        foreach (array_keys($convocatoria->applicantDocumentRequirements()) as $key) {
            $documents[$key] = UploadedFile::fake()->createWithContent(
                $key.'.pdf',
                "%PDF-1.4\nContenido de prueba\n%%EOF",
            );
        }

        $this->actingAs($applicant)
            ->post(route('postulaciones.store', $convocatoria), ['documentos' => $documents])
            ->assertRedirect(route('dashboard'));

        $postulacion = Postulacion::query()->with('documentos')->firstOrFail();
        $this->assertCount(count($documents), $postulacion->documentos);
        Http::assertNothingSent();

        foreach ($postulacion->documentos as $documento) {
            $this->assertSame('local', $documento->storage_disk);
            Storage::disk('local')->assertExists($documento->path);
        }
    }

    private function createPostulacion(): Postulacion
    {
        $applicant = User::factory()->create(['role' => User::ROLE_USER]);
        $convocatoria = $this->createConvocatoria();

        return Postulacion::query()->create([
            'convocatoria_id' => $convocatoria->id,
            'user_id' => $applicant->id,
            'estatus' => Postulacion::STATUS_PENDING,
        ]);
    }

    private function createConvocatoria(): Convocatoria
    {
        return Convocatoria::query()->create([
            'titulo' => 'Convocatoria de prueba',
            'tipo_concurso' => 'Abierta',
            'tipo_personal' => 'No docente',
            'categoria_nivel' => 'Administrativo',
            'plaza_disponible' => '2 plazas',
            'jornada_horario' => 'Tiempo completo',
            'salario' => '18000.00',
            'fecha_inicio' => today()->toDateString(),
            'fecha_fin' => today()->addMonth()->toDateString(),
            'estatus' => 'Activa',
        ]);
    }
}
