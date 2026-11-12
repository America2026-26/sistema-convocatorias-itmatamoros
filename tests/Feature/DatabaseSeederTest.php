<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_the_three_role_accounts_with_the_requested_password(): void
    {
        $this->seed();

        $accounts = [
            'admin@itm.mx' => User::ROLE_ADMIN,
            'dict@itm.mx' => User::ROLE_REVIEWER,
            'user@itm.mx' => User::ROLE_USER,
        ];

        $this->assertDatabaseCount('users', count($accounts));

        foreach ($accounts as $email => $role) {
            $user = User::query()->where('email', $email)->firstOrFail();

            $this->assertSame($role, $user->role);
            $this->assertTrue(Hash::check('12345678', $user->password));
        }

        $teacher = User::query()->where('email', 'user@itm.mx')->firstOrFail();

        $this->assertSame('aspirante_docente', $teacher->tipo_usuario);
        $this->assertSame('E3817', $teacher->plaza_jornada);
    }
}
