<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public static function userRoles(): array
    {
        return [
            'applicant' => [User::ROLE_USER],
            'reviewer' => [User::ROLE_REVIEWER],
            'administrator' => [User::ROLE_ADMIN],
        ];
    }

    #[DataProvider('userRoles')]
    public function test_users_of_every_role_can_view_their_registered_profile(string $role): void
    {
        $user = User::factory()->create([
            'role' => $role,
            'name' => 'Ana López Ruiz',
            'email' => 'ana@example.com',
            'nombre' => 'Ana',
            'apellido_paterno' => 'López',
            'apellido_materno' => 'Ruiz',
            'curp' => 'ABCD001231HDFXXX01',
            'rfc' => 'ABC001231XYZ',
            'tipo_usuario' => 'aspirante_docente',
        ]);

        $response = $this->actingAs($user)->get(route('profile.show'));

        $response->assertOk()
            ->assertSee('Mi perfil')
            ->assertSee('Ana López Ruiz')
            ->assertSee('ana@example.com')
            ->assertSee('ABCD001231HDFXXX01')
            ->assertSee('ABC001231XYZ')
            ->assertSee('aspirante_docente');
    }

    public function test_unauthenticated_visitors_cannot_view_profile_or_settings(): void
    {
        $this->get(route('profile.show'))->assertRedirect(route('login'));
        $this->get(route('profile.settings'))->assertRedirect(route('login'));
        $this->get(route('profile.photo'))->assertRedirect(route('login'));
    }

    #[DataProvider('userRoles')]
    public function test_users_of_every_role_can_upload_and_view_their_profile_photo(string $role): void
    {
        Storage::fake('local');
        $user = User::factory()->create(['role' => $role]);

        $this->actingAs($user)
            ->post(route('profile.photo.update'), [
                'photo' => $this->fakeProfilePhoto('foto.png'),
            ])
            ->assertRedirect(route('profile.show'))
            ->assertSessionHas('status');

        $photoPath = $user->refresh()->profile_photo_path;
        $this->assertNotNull($photoPath);
        Storage::disk('local')->assertExists($photoPath);

        $photoResponse = $this->get(route('profile.photo'));

        $photoResponse
            ->assertOk()
            ->assertHeader('content-type', 'image/png');

        $this->assertStringContainsString('no-store', $photoResponse->headers->get('cache-control'));
        $this->assertStringContainsString('private', $photoResponse->headers->get('cache-control'));
        $this->assertStringNotContainsString('public', $photoResponse->headers->get('cache-control'));

        $this->get(route('profile.show'))
            ->assertOk()
            ->assertSee(route('profile.photo', ['v' => $photoPath]))
            ->assertSee('data-photo-modal-open', false)
            ->assertSee('role="dialog"', false)
            ->assertSee('Vista previa del recorte de foto')
            ->assertSee('data-photo-crop-preview', false)
            ->assertSee('data-photo-zoom', false)
            ->assertSee('Cambiar foto')
            ->assertSee('Eliminar foto');
    }

    public function test_user_can_replace_and_delete_their_profile_photo(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('profile.photo.update'), [
            'photo' => $this->fakeProfilePhoto('primera.png'),
        ])->assertRedirect(route('profile.show'));
        $oldPath = $user->refresh()->profile_photo_path;

        $this->post(route('profile.photo.update'), [
            'photo' => $this->fakeProfilePhoto('segunda.png'),
        ])->assertRedirect(route('profile.show'));
        $newPath = $user->refresh()->profile_photo_path;

        $this->assertNotSame($oldPath, $newPath);
        $this->assertSame($newPath, $user->profile_photo_path);
        Storage::disk('local')->assertMissing($oldPath);
        Storage::disk('local')->assertExists($newPath);

        $this->get(route('profile.show'))
            ->assertOk()
            ->assertSee(route('profile.photo', ['v' => $newPath]));

        $this->delete(route('profile.photo.destroy'))
            ->assertRedirect(route('profile.show'))
            ->assertSessionHas('status');

        $this->assertNull($user->refresh()->profile_photo_path);
        Storage::disk('local')->assertMissing($newPath);
        $this->get(route('profile.show'))
            ->assertOk()
            ->assertDontSee('Eliminar foto');
    }

    public function test_profile_photo_upload_rejects_non_image_files(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('profile.show'))
            ->post(route('profile.photo.update'), [
                'photo' => UploadedFile::fake()->create('documento.pdf', 100, 'application/pdf'),
            ])
            ->assertRedirect(route('profile.show'))
            ->assertSessionHasErrors('photo');

        $this->assertNull($user->refresh()->profile_photo_path);
        $this->assertSame([], Storage::disk('local')->allFiles('profile-photos/'.$user->id));
    }

    public function test_authenticated_user_can_view_settings_placeholder_and_profile_menu(): void
    {
        $user = User::factory()->create(['email' => 'ana@example.com']);

        $this->actingAs($user)
            ->get(route('profile.settings'))
            ->assertOk()
            ->assertSee('Las opciones de configuración estarán disponibles próximamente.')
            ->assertSee('data-profile-toggle', false)
            ->assertSee('data-theme-toggle', false)
            ->assertSee('Mi perfil')
            ->assertSee('Configuración');
    }

    private function fakeProfilePhoto(string $name): UploadedFile
    {
        $content = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/6WQAAAAASUVORK5CYII=',
            true,
        );

        if ($content === false) {
            throw new \RuntimeException('Could not create the PNG test fixture.');
        }

        return UploadedFile::fake()->createWithContent($name, $content);
    }
}
