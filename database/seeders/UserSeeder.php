<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('SEED_ADMIN_PASSWORD', '12345678');

        if (app()->environment('production') && $password === '12345678') {
            $this->command?->warn('Refusing weak default password in production. Set SEED_ADMIN_PASSWORD.');
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
