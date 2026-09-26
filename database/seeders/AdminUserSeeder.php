<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = config('admin.email');
        $password = config('admin.password');

        if (! is_string($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL) || ! is_string($password) || mb_strlen($password) < 12) {
            $this->command?->warn('Admin account skipped. Set a valid ADMIN_EMAIL and an ADMIN_PASSWORD of at least 12 characters.');

            return;
        }

        User::query()->updateOrCreate(
            ['email' => Str::lower($email)],
            [
                'name' => config('admin.name', 'Back2Value Admin'),
                'password' => $password,
                'email_verified_at' => now(),
            ],
        );

        $this->command?->info('Configured the Back2Value admin account.');
    }
}
