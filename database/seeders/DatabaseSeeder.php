<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrador',
                'email' => 'admin@itm.mx',
                'role' => User::ROLE_ADMIN,
            ],
            [
                'name' => 'Dictaminador',
                'email' => 'dict@itm.mx',
                'role' => User::ROLE_REVIEWER,
            ],
            [
                'name' => 'Usuario',
                'email' => 'user@itm.mx',
                'role' => User::ROLE_USER,
                'tipo_usuario' => 'aspirante_docente',
                'plaza_jornada' => 'E3817',
            ],
        ];

        foreach ($users as $user) {
            User::query()->updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => '12345678',
                    'role' => $user['role'],
                    'tipo_usuario' => $user['tipo_usuario'] ?? null,
                    'plaza_jornada' => $user['plaza_jornada'] ?? null,
                ],
            );
        }
    }
}
