<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('SEED_ADMIN_PASSWORD');

        if (! $password) {
            if (app()->environment('production')) {
                $password = bin2hex(random_bytes(12));
                $this->command?->warn('SEED_ADMIN_PASSWORD not set — generated one-time admin password: '.$password);
                $this->command?->warn('Add SEED_ADMIN_PASSWORD to .env and change passwords after first login.');
            } else {
                $password = '12345678';
            }
        } elseif (app()->environment('production') && $password === '12345678') {
            $this->command?->error('Refusing weak default password in production. Set SEED_ADMIN_PASSWORD in .env.');

            return;
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make($password),
            ]
        );
        $admin->assignRole('admin');

        $moderator = User::firstOrCreate(
            ['email' => 'moderator@moderator.com'],
            [
                'name' => 'Moderator User',
                'password' => Hash::make($password),
            ]
        );
        $moderator->assignRole('moderator');

        $blogger = User::firstOrCreate(
            ['email' => 'blogger@blogger.com'],
            [
                'name' => 'Blogger User',
                'password' => Hash::make($password),
            ]
        );
        $blogger->assignRole('blogger');
    }
}
