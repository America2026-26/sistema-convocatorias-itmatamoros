<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

#[Signature('app:create-admin-user')]
#[Description('Create the first system administrator account')]
class CreateAdminUser extends Command
{
    public function handle(): int
    {
        $name = trim((string) $this->ask('Administrator name'));
        $email = Str::lower(trim((string) $this->ask('Administrator email')));

        if ($name === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('A name and a valid email address are required.');

            return self::FAILURE;
        }

        if (User::query()->where('email', $email)->exists()) {
            $this->error('A user with that email address already exists.');

            return self::FAILURE;
        }

        $password = (string) $this->secret('Administrator password (minimum 12 characters)');
        $confirmation = (string) $this->secret('Confirm password');

        if (mb_strlen($password) < 12 || ! hash_equals($password, $confirmation)) {
            $this->error('The password must have at least 12 characters and both entries must match.');

            return self::FAILURE;
        }

        DB::transaction(function () use ($name, $email, $password): void {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => User::ROLE_ADMIN,
            ]);

            AuditLog::record('user.created', null, $user, ['source' => 'console']);
        });

        $this->info("Administrator account created for {$email}.");

        return self::SUCCESS;
    }
}
